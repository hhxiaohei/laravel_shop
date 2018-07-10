<?php

namespace App\Listeners;

use App\Events\OrderReviewd;
use App\Models\OrderItem;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class UpdateProductRating implements ShouldQueue
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
     * @param  OrderReviewd  $event
     * @return void
     */
    public function handle(OrderReviewd $event)
    {
        $items = $event->getOrder()->items()->with('product')->get();
        foreach ($items as $item){
            $res = OrderItem::whereProductId($item->product->id)
                ->whereHas('order',function ($q){
                    $q->whereNotNull('paid_at');
                })->first([
                    DB::raw("count(*) as review_count"),
                    DB::raw("avg(rating) as rating"),
                ])->toArray();
            $item->product->update(array_only($res,[
                'review_count',
                'rating',
            ]));
        }
    }
}
