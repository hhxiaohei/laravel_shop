<?php

Route::get('/','PageController@root')->name('root');
Auth::routes();

Route::group(['middleware'=>'auth'],function(){
    Route::get('email_verify_notice','PageController@emailVerifyNotice')->name('email_verify_notice');
    Route::get('email_verification/verify','EmailVerificationController@verify')->name('email_verification.verify');//激活邮箱
    Route::get('email_verification/send','EmailVerificationController@send')->name('email_verification.send');//重新发邮件
    //邮箱验证中间件
    Route::group(['middleware'=>'email_verified'],function(){
        Route::get('user_addresses','UserAddressController@index')->name('user_addresses.index');//重新发邮件
    });
});