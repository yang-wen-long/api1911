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
   // phpinfo();
    return view('welcome');
});
Route::get('/php', function () {
     phpinfo();
});

//后台注册
Route::post("/user/reg","Admin\LoginController@Reg");
//后台登陆
Route::post("/user/Login","Admin\LoginController@Login");
//后台展示用户方法
Route::post("/user/center","Admin\LoginController@center")->middleware("isLogin");

//使用Redis有序集合实现签到功能：
Route::get("/user/qiandao","Admin\LoginController@qiandao");


//用redis缓存用户信息
Route::get("/user/Signin","Admin\LoginController@Signin");

//使用Redis中Hash实现每个用户访问的接口统计
Route::get("/user/stati","Admin\LoginController@stati")->middleware("fangwen");


//商品的抢购
Route::post("/user/goods","Api\TestController@goods")->middleware("fangwen");







//调用微信Token方法
Route::get("/Token","Api\TestController@index")->middleware("isLogin");
Route::get("/user/Info","Api\TestController@info");




