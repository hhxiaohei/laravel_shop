<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\OrderItem;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductSoldCount implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();
        foreach ($order->items as $item){
            $product = $item->product;
            //1.计算总销量
            $soldCount = OrderItem::query()
                ->where('product_id' , $product->id)
                ->whereHas('order',function ($query){
                    $query->whereNotNull('paid_at');
                })->sum('amount');
            //2.自增
//            $count = OrderItem::query()
//                ->where('product_id' , $product->id)
//                ->where('order_id' , $order->id)
//                ->count();
//            $product->incremet('sold_count' , $count);
            //更新商品销量
            $product->sold_count = $soldCount;
            $product->save();
        }
    }
}
