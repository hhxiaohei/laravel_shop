<?php

namespace App\Admin\Controllers;

use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Admin\HandleRefundRequest;
use App\Models\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('订单列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->model()->whereNotNull('paid_at')->orderBy('paid_at');
            $grid->columns([
                'total_amount' => '订单总金额',
                'paid_at'      => '支付时间',
                'user.name'    => '买家',
                'no'           => '订单流水号',
            ]);
            $grid->ship_status('物流状态')->display(function ($item) {
                return Order::$shipStatusMap[$item];
            });
            $grid->refund_status('退款状态')->display(function ($item) {
                return Order::$refundStatusMap[$item];
            });

            $grid->disableCreateButton();
            //删除 编辑
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();

                $actions->append("<a href='" . route('admin.orders.show', ['id' => $actions->getKey()]) . "'. class='btn btn-primary btn-xs'>查看</a>");
            });
            //批量删除
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    //自定义显示详情
    public function show(Order $order)
    {
        return Admin::content(function (Content $content) use (&$order) {
            $content->header('查看订单');
            $content->body(view('admin.orders.show', compact('order')));
        });
    }

    //发货
    public function ship(Order $order, Request $request)
    {
        //支付
        if (!$order->paid_at)
            throw new InvalidRequestException('订单未支付');

        //发货
        if ($order->ship_status !== Order::SHIP_STS_PENDING)
            throw new InvalidRequestException('订单已经发货');

        //验证 5.5之后可以返回检验过的值
        $data = $this->validate($request, [
            'express_company' => ['required'],
            'express_no'      => ['required'],
        ], [], [
            'express_company' => '物流公司',
            'express_no'      => '物流单号',
        ]);
//        dd($data);

        $order->update([
            'ship_status' => Order::SHIP_STS_DELIVERED,
            'ship_data'   => $data,
        ]);

        admin_toastr('发货成功!');
        return redirect()->back();
    }

    public function handleRefund(Order $order , HandleRefundRequest $request)
    {
        if($order->refund_status !== Order::REFUND_STATUS_APPLIED) throw new InvalidRequestException('订单状态不正确');

        if($request->agree){
            $this->_refundOrder($order);
        }else{
            //改为未退款状态
            $extra = $order->extra ? : [];
            $extra['refund_disagree_reason'] = $request->reason;
            $order->extra = $extra;
            $order->refund_status = Order::REFUND_STATUS_PENDING;
            $order->save();
        }

        return $order;
    }

    public function _refundOrder(Order $order)
    {
        switch ($order->payment_method){
            case 'wechat':
                $refundNo = Order::getAvailableRefundNo();
                app('wechat_pay')->refund([
                    'out_trade_no'   => $order->no,
                    'total_fee'      => $order->total_amount * 100,
                    'refund_fee'     => $order->total_amount * 100,
                    'out_request_no' => $refundNo,
                    'notify_url'     => route('payment.wechat.refund.notify'),
                ]);
                $order->update([
                    'refund_no'     => $refundNo,
                    'refund_status' => Order::REFUND_STATUS_SUCCESS,
                ]);
                break;
            case 'alipay':
                $refundNo = Order::getAvailableRefundNo();
                $ret = app('alipay')->refund([
                    'out_trade_no'   => $order->no,
                    'refund_amount'  => $order->total_amount,
                    'out_request_no' => $refundNo,
                ]);
                if($ret->sub_code){
                    $extra = $order->extra;
                    $extra['refund_failed_code'] = $ret->sub_code;
                    $order->update([
                        'refund_no'=>$refundNo,
                        'refund_status'=>Order::REFUND_STATUS_FAILED,
                        'extra'=>$extra,
                    ]);
                }else{
                    $order->update([
                        'refund_no'=>$refundNo,
                        'refund_status'=>Order::REFUND_STATUS_SUCCESS
                    ]);
                }
                break;
            default:
                throw new InvalidRequestException('退款方式错误:' . $order->payment_method);
                break;
        }

        return $order;
    }
}
