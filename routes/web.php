<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// 以上方為例，設定當使用者要get'/'時，就執行函數來回傳resources/views/welcome.blade.php
// 以下方為例，設定當使用者要post'/'時，就進到名為'ProductController'的Controller，並使用create函數
// Route::post('/','ProductController@create');

// Route::resourse('product','ProductController');
// 此為針對product去產生各種網址，並且對應到ProductController。

// 基本觀念
    // CRUD：Create、Read、Update、Delete
    // Controller:是核心的邏輯集中地，為各種Controller的被繼承物件

// 終端操作
    // php artisan route:list : 請終端靠訴我們目前有哪些路由
    // php artisan make:controller Controller名稱:請artisan幫忙建立一個Controller。

// 基本程式碼
    // 1.根目錄路由
    //      解析：設定當使用者要get'/'時，就執行函數來回傳resources/views/welcome.blade.php
    //      程式碼範例：
    //              Route::get('/', function () {
    //                     return view('welcome');
    //              });

    // 2.進到Controller
    //      解析：設定當使用者要post'/'時，就進到名為'ProductController'的Controller，並使用create函數。
    //      程式碼範例：
    //              Route::post('/','ProductController@create');

    // 3.針對某詞產生各種網址
    //      解析：此為針對product去產生各種網址，並且對應到ProductController。
    //      程式碼範例：
    //              Route::resourse('product','ProductController');
        

// 流程操作
    // 1.終端-php artisan route:list : 請終端靠訴我們目前有哪些路由
    // 2.終端-php artisan make:controller Controller名稱:請artisan幫忙建立一個Controller。


