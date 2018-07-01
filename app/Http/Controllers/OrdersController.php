<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalException;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['items.product','items.productSku'])->orderBy('id','desc')->paginate(5);
        return view('orders.index',compact('orders'));
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
    public function store(OrderRequest $request , Order $order)
    {
//        dd($request->toArray());
        $user = $request->user();
        $total_amount = 0;
        $items = $request->items;
        $address = UserAddress::find($request->address_id);

        DB::beginTransaction();
        try {
            $address->last_used_at = now();
            $address->save();

            $order->address = $address->only([
                'province',
                'city',
                'district',
                'address',
                'contact_name',
                'contact_phone',
                'zip',
                'full_address',
            ]);
            $order->user()->associate($user);
            $order->note = $request->note ?? null;
            $order->save();
//            Log::info(json_encode($order));

            //sku
            foreach ($items as $item) {
                $sku = ProductSku::find($item['sku_id']);
                //create order_items
                // new OrderItem
                $product_item = $order->items()->make([
                    'amount' => $item['amount'],
                    'price'  => $sku->price,
                ]);
                $product_item->product()->associate($sku->product_id);
                $product_item->productSku()->associate($sku);
                $product_item->save();
                if(!$sku->decrementStock($item['amount']))
                    throw new InternalException('库存不足');
                $total_amount += $sku->price * $item['amount'];
            }
            $order->total_amount = $total_amount;
            $order->save();

            //remove cart
            $skuIds = array_pluck($items, 'sku_id');
            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
            dispatch(new CloseOrder($order , config('app.queue_ttl')));
            DB::commit();
            Log::debug('Order store success!');
        } catch (\Exception $e) {
            Log::error('Order store fail' . $e->getMessage() . $e->getLine());
            DB::rollBack();
            return [];
        }

        return $order;

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('own' , $order);
        $order = $order->load(['items.product','items.productSku']);
        return view('orders.show',compact('order'));
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
}
