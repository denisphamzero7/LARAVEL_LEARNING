<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Modules\user\src\http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by lthe RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('demo')->get('/user', function (Request $request) {
//     return config('user.config.test');
// });

// Route::middleware('demo')->get('/user',[UserController::class,'index']);

Route::group(['namespace' => 'Modules\user\src\http\Controllers'], function () {
    Route::prefix('user')->group(function () {
        Route::get('/', 'UserController@index');
        Route::get('/detail/{id}', 'UserController@detail');
        Route::get('/create', 'UserController@create');
    });
});