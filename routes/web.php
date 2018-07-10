<?php

Route::redirect('/', 'products')->name('root');
Auth::routes();
//Route::get('ali',function(){
//    return app('alipay')->web([
//        'out_trade_no'=>mt_rand(11111,99999),
//        'total_amount'=>1,
//        'subject'=>'测试支付',
//    ]);
//});
Route::group(['middleware' => 'auth'], function () {
    Route::get('email_verify_notice', 'PageController@emailVerifyNotice')->name('email_verify_notice');
    Route::get('email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify');//激活邮箱
    Route::get('email_verification/send', 'EmailVerificationController@send')->name('email_verification.send');//重新发邮件
    //邮箱验证中间件
    Route::group(['middleware' => 'email_verified'], function () {
        Route::get('user_addresses', 'UserAddressController@index')->name('user_addresses.index');
        Route::get('user_addresses/create', 'UserAddressController@create')->name('user_addresses.create');
        Route::post('user_addresses', 'UserAddressController@store')->name('user_addresses.store');
        Route::get('user_addresses/{address}', 'UserAddressController@edit')->name('user_addresses.edit');
        Route::put('user_addresses/{address}', 'UserAddressController@update')->name('user_addresses.update');
        Route::delete('user_addresses/{address}', 'UserAddressController@destroy')->name('user_addresses.destroy');
        //关注列表
        Route::get('products/favorites','ProductsController@favorites')->name('products.favorites');
        //添加收藏
        Route::post('product/{product}/favorite','ProductsController@favorite')->name('product.favorite');
        //取消关注
        Route::delete('product/{product}/favorite','ProductsController@disfavorite')->name('product.disfavorite');
        //购物车
        Route::resource('cart','CartController');
        //订单收货
        Route::patch('orders/{order}/received','OrdersController@received')->name('orders.received');
        //下订单
        Route::resource('orders' , 'OrdersController');
        //支付宝支付
        Route::get('payment/{order}/alipay' , 'PaymentController@payByAliPay')->name('payment.alipay');
        //微信支付
        Route::get('payment/{order}/wechat' , 'PaymentController@payByWechat')->name('payment.wechat');
        //支付宝 前端回调
        Route::get('payment/alipay/return','PaymentController@alipayReturn')->name('payment.alipay.return');
        //用户评价
        Route::post('orders/{order}/review','OrdersController@sendReview')->name('orders.review.send');
        //用户评价信息
        Route::get('orders/{order}/review','OrdersController@review')->name('orders.review.show');
    });
});

//商品
Route::resource('products', 'ProductsController')->only(['index', 'show']);
//支付宝 支付 服务器回调
Route::post('payment/alipay/notify','PaymentController@alipayNotify')->name('payment.alipay.notify');
//微信支付 服务器回调
Route::post('payment/wechat/notify','PaymentController@wechatPayNotify')->name('payment.wechat.notify');