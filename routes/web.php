<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//商品列表GoodsController
Route::get('/goods/goodslist', 'Goods\GoodsController@Goodslist');
Route::post('/cart/cartlist', 'Cart\CartController@Cartlist');
Route::get('/cart/cartdetail', 'Cart\CartController@Cartdetail');
//删除
Route::get('/cart/deletecartgoods', 'Cart\CartController@deletecartgoods');
Route::post('/cart/updatenum', 'Cart\CartController@updatenum');
Route::get('/order/orderdetail', 'Order\OrderController@orderdetail');
//Route::post('/order/orderpay', 'Order\OrderController@orderpay');
//
//支付
Route::post('/order/order', 'Order\OrderController@order');
Route::get('/pay/payadd','Pay\PayController@pay');    //支付
Route::get('/test','Pay\PayController@test');    //测试
Route::get('/Alireturn','Pay\PayController@Alireturn');    //同步通知
Route::post('/notify','Pay\PayController@notify');    //异步通知