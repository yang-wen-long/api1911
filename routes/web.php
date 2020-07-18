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
Route::post("/user/center","Admin\LoginController@center");

//测试方法
Route::get("/text","Admin\LoginController@text");



Route::get("/Token","Api\TestController@index");
Route::get("/user/Info","Api\TestController@info");



