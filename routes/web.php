<?php

Route::redirect('/', 'products')->name('root');
Auth::routes();

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
        Route::apiResource('cart','CartController');
    });
});

//商品
Route::resource('products', 'ProductsController')->only(['index', 'show']);