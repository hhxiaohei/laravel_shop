<?php

namespace App\Http\Controllers;

use App\Events\OrderReviewd;
use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SendReviewRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class OrdersController
 * @package App\Http\Controllers
 */
class OrdersController extends Controller
{
    /**
     * @var CartService
     */
//    protected $cartService;
//    /**
//     * OrdersController constructor.
//     */
//    public function __construct(CartService $cartService)
//    {
//        $this->cartService = $cartService;
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['items.product', 'items.productSku'])->orderBy('id', 'desc')->paginate(5);
        return view('orders.index', compact('orders'));
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
    public function store(OrderRequest $request, OrderService $orderService)
    {
        return $orderService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('own', $order);
        $order = $order->load(['items.product', 'items.productSku']);
        return view('orders.show', compact('order'));
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

    // 用户 收货
    public function received(Order $order, Request $request)
    {
        $this->authorize('own', $order);

        if ($order->ship_status !== Order::SHIP_STS_DELIVERED)
            throw new InvalidRequestException('订单未发货');

        $order->ship_status = Order::SHIP_STS_RECEIVED;
        $order->save();

        return $order;
//        return redirect()->back();
    }

    //查看评价
    public function review(Order $order)
    {
        $this->authorize('own', $order);
        if (!$order->paid_at)
            throw new InvalidRequestException('该订单未支付');

        $order = $order->load(['items.productSku', 'items.product']);
        return view('orders.review', compact('order'));
    }

    //用户评价
    public function sendReview(Order $order, SendReviewRequest $request)
    {
        $this->authorize('own', $order);
        if (!$order->paid_at)
            throw new InvalidRequestException('该订单未支付');
        if ($order->reviewed)
            throw new InvalidRequestException('该订单已经评价');

        $reviews = $request->reviews;

        DB::beginTransaction();
        try {
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                $orderItem->update(array_merge(array_only($review, ['rating', 'review']), ['reviewed_at' => now()]));
            }
            $order->reviewed = true;
            $order->save();
            Log::info('ssss');
            event(new OrderReviewd($order));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
        }
//        return $order;
        return redirect()->back();

    }

    //退款
    public function applyRefund(Order $order , ApplyRefundRequest $request)
    {
        $this->authorize('own', $order);

        if (!$order->paid_at) throw new InvalidRequestException('该订单未支付,不可退款');

        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) throw new InvalidRequestException('该订单已经申请退款了!');

        //extra
        $extra = $order->extra ?: [];
        $extra['refund_reason'] = $request->reason;

        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);
        return $order;
    }
}
