<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Modules\Product\src\http\Controllers'], function () {
    Route::prefix('product')->group(function () {
        Route::get('/', 'ProductController@index');
    });
});
