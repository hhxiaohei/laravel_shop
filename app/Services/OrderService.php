<?php

namespace App\Services;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\ProductSku;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public $order;
    public $cartService;
    /**
     * OrderService constructor.
     */
    public function __construct(Order $order , CartService $cartService)
    {
        $this->order = $order;
        $this->cartService = $cartService;
    }

    public function store($request)
    {
        $user = $request->user();
        $total_amount = 0;
        $items = $request->items;
        $address = UserAddress::find($request->address_id);
        $order = $this->order;

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
            //$user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
            $this->cartService->remove($skuIds);
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
}