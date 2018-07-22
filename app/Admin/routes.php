<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('users', 'UserController@index');
    $router->resource('products','ProductController');
    $router->post('orders/{order}/ship' , 'OrdersController@ship')->name('admin.orders.ship');
    $router->resource('orders','OrdersController')->names('admin.orders');
    $router->post('orders/{order}/refund','OrdersController@handleRefund')->name('admin.order.handle_refund');
    $router->resource('coupon_codes','CouponCodesController')->names('admin.coupon.codes');
});
