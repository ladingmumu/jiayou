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

//首页
Route::get('/','StaticPagesController@home')->name('home');
//帮助页
Route::get('/help','StaticPagesController@help')->name('help');
//关于页
Route::get('/about','StaticPagesController@about')->name('about');

//注册页面
Route::get('/signup','UsersController@create')->name('signup');

//用户资源路由
Route::resource('users','UsersController');

//显示登录页面
Route::get('login','SessionsController@create')->name('login');

//用户登录
Route::post('login','SessionsController@store')->name('login');

//退出登录
Route::delete('logout','SessionsController@destroy')->name('logout');









