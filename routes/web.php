<?php

Route::get('/','PageController@root')->name('root');
Auth::routes();