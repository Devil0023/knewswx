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

Route::group(["prefix" => "wechat", 'middleware' => ['web', 'wechat.oauth:snsapi_userinfo', "knews.register"]], function () {
//    Route::get('/user', function () {
//        $user = session('wechat.oauth_user'); // 拿到授权用户资料
//        dd($user);
//    });
    //奖品列表
    Route::get("/prize/list", "WechatController@prizelist");
    //奖品详情
    Route::get("/prize/detail/{id}", "WechatController@prize");

    //用户中心
    Route::get("/usercenter/index", "WechatController@index");
    //完善信息
    Route::post("/usercenter/update", "WechatController@updateUser");
    //积分明细
    Route::get("/usercenter/detail", "WechatController@detail");
    //兑换奖品
    Route::post("/usercenter/exchange/{pid}", "WechatController@exchange");
    //签到
    Route::post("/usercenter/sign", "WechatController@sign");


});