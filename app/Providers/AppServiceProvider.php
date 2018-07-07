<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Yansongda\Pay\Pay;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //alipay
        $this->app->singleton('alipay', function () {
            $config = config('pay.alipay');
            $config['notify_url']=route('payment.alipay.notify');
            //设置日志等级
            if (app()->environment() != 'production') {
                $config['mode'] = 'dev';
                $config['log']['level'] = Logger::DEBUG;
                $config['notify_url']='http://requestbin.leo108.com/11836vh1';
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            $config['return_url']=route('payment.alipay.return');
            return Pay::alipay($config);
        });

        //wechatPay
        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            if (app()->environment() !== 'production') {
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            return Pay::wechat($config);
        });

        //debug
//        if ($this->app->environment() == 'local') {
//            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
//        }
    }
}
