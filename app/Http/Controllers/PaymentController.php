<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Exceptions\InternalException;
use App\Models\Order;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function payByAliPay(Order $order, Request $request)
    {
        $this->validateOrder($order);
        return app('alipay')->web([
            'out_trade_no' => $order->no,
            'total_amount' => $order->total_amount,//支付宝支付单位为元
            'subject'      => '订单号' . $order->no,
        ]);
    }

    //todo 微信支付
    public function payByWechat(Order $order, Request $request)
    {
        $this->validateOrder($order);
        //微信扫码支付
        $wechatOrder = app('wechat_pay')->scan([
            'out_trade_no' => $order->no,
            'total_amount' => $order->total_amount * 100,//微信支付单位为分
            'subject'      => '订单号' . $order->no,
        ]);
        $qrcode = new QrCode($wechatOrder->code_url);
        return response($qrcode->writeString(), 200, [
            'Content-Type' => $qrcode->getContentType(),
        ]);

    }

    public function validateOrder($order)
    {
        $this->authorize('own', $order);
        if ($order->paid_at || $order->closed) {
            throw new InternalException('订单状态不正确');
        }
    }

    public function alipayReturn()
    {
        try {
            app('alipay')->verify();
        } catch (\Exception $exception) {
            return view('pages.error', ['msg' => '数据错误']);
        }
        return view('pages.success', ['msg' => '支付成功']);
    }

    public function alipayNotify()
    {
        $data = app('alipay')->verify();
        \Log::debug('alipay notify==>' . json_encode($data));
        $order = Order::whereNo($data->out_trade_no)->first();
        if (!$order) {
            return [false, 'fail'];
        }

        if ($order->paid_at || $order->closed) {
            return [false, 'paid or close'];
        }

        $order->paid_at = now();
        $order->payment_method = Order::PAYMENT_METHOD_ALIPAY;
        $order->payment_no = $data->out_trade_no;
        $order->save();
        $this->afterPaid($order);
        return app('alipay')->success();
    }

    //todo 微信支付回调
    public function wechatPayNotify()
    {
        $data = app('wechat_pay')->verify();
        $order = Order::whereNo($data->our_trade_no)->first();
        if (!$order) {
            return 'fail';
        }
        if ($order->paid_at) {
            return app('wechat_pay')->success();
        }
        $order->paid_at = now();
        $order->payment_method = Order::PAYMENT_METHOD_WECHAT;
        $order->payment_no = $data->transaction_id;
        $order->save();
        $this->afterPaid($order);
        return app('wechat_pay')->success();
    }

    public function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }

    public function wechatRefundNotify(Request $request)
    {
        $failXml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        $input = parse_xml($failXml);

        if(!$input || empty($input['req_info'])) return $failXml;

        $encryptedXml = base64_decode($input['req_info'] , 1);
        $decryptedXml = openssl_decrypt($encryptedXml,'AES-256-ECB',md5(config('pay.wechat.key')), OPENSSL_RAW_DATA, '');

        if(!$decryptedXml) return $decryptedXml;

        $decryptedData = parse_xml($decryptedXml);

        if(!$order = Order::whereNo($decryptedData['out_trade_no'])->first())
            return $failXml;

        if($decryptedData['refund_status'] === 'SUCCESS'){
            $order->update([
                'refund_status'=>Order::REFUND_STATUS_SUCCESS,
            ]);
        }else{
            $extra = $order->extra;
            $extra['refund_failed_code'] = $decryptedData['refund_status'];
            $order->update([
                'refund_status'=>Order::REFUND_STATUS_FAILED
            ]);
        }

        return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';

    }
}
