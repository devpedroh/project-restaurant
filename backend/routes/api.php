<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/notifications', function (Request $req) {
    if (!empty($req->token)) {
        return view('notifications');
    } else {
        abort(401);
    }
});
Route::middleware('auth:api')->post('/save-token', 'PushController@saveToken')->name('save-token');
Route::middleware('auth:api')->post('/disable-token', 'PushController@disableToken')->name('disable-token');
Route::post('/send-notification', 'PushController@sendNotification')->name('send.notification');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'UserController@login');
Route::middleware('auth:api')->post('logout', 'UserController@logout');

Route::prefix('products')->group(function () {
    Route::post('get', 'ProductController@get');
    Route::post('getTop', 'ProductController@getTop');
});

Route::prefix('orders')->group(function () {
    Route::post('create', 'OrderController@create');
    Route::post('get', 'OrderController@get');
    Route::post('view', 'OrderController@view');
    Route::post('end', 'OrderController@end');
});
