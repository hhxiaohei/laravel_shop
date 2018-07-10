<?php

namespace App\Providers;

use App\Events\OrderPaid;
use App\Events\OrderReviewd;
use App\Listeners\RegisteredListener;
use App\Listeners\SendOrderEmail;
use App\Listeners\UpdateProductRating;
use App\Listeners\UpdateProductSoldCount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event'  => [
            'App\Listeners\EventListener',
        ],
        //用户注册以后发送激活邮件(放队列执行)
        Registered::class   => [
            RegisteredListener::class,
        ],
        //订单支付以后
        OrderPaid::class    => [
            UpdateProductSoldCount::class,
            //发邮件
            SendOrderEmail::class,
        ],
        //评价关联商品
        OrderReviewd::class => [
            UpdateProductRating::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
