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

Route::resource('product','ProductController');
// 此為針對product去產生各種網址，並且對應到ProductController。


Route::post('signup','AuthController@signup');
Route::post('login','AuthController@login');
Route::group([
    'middleware'=> 'auth:api'
],function(){
    Route::get('user','AuthController@user');
    Route::get('logout','AuthController@logout');
    Route::post('carts/checkout','CartController@checkout');
    Route::resource('carts','CartController');
    Route::resource('cart-items','CartItemController'); // 官方建議使用'-'而非'_'
});


Route::group(['middleware'=>'check.dirty'],function(){
    Route::resource('product','ProductController');
});
// 基本觀念
    // CRUD：Create、Read、Update、Delete
    // Controller:是核心的邏輯集中地，為各種Controller的被繼承物件
    // php artisan route:list裡面的product/{product}，其中的"{product}"是參數的意思，
            // 例如：product/{product} ..product.show › ProductController@show ，
            // {product}就會是public function show(string $id)的$id。

// 終端操作
    // php artisan route:list : 請終端靠訴我們目前有哪些路由

    // php artisan make:controller Controller名稱:請artisan幫忙建立一個在app/Http/Controller底下的Controller

    // php artisan make:controller Controller名稱 --resource:
        // 請artisan幫忙建立一個在app/Http/Controller底下的Controller，並產生各種(CRUD)函式。

    // composer dump-autoload:請server重新讀取新的檔案



// 基本程式碼
    // 1.根目錄路由(常用)
    //      解析：設定當使用者要get'/'時，就執行函數來回傳resources/views/welcome.blade.php
    //      程式碼範例：
    //              Route::get('/', function () {
    //                     return view('welcome');
    //              });

    // 2.進到Controller
    //      解析：設定當使用者要post'/'時，就進到名為'ProductController'的Controller，並使用create函數。
    //      程式碼範例：
    //              Route::post('/','ProductController@create');

    // 3.針對某詞產生各種網址(常用)
    //      解析：此為針對product去產生各種網址，並且對應到ProductController。
    //      程式碼範例：
    //              Route::resource('product','ProductController');
        

// 流程操作
    // 1.終端-php artisan route:list : 請終端靠訴我們目前有哪些路由
    // 2.終端-php artisan make:controller Controller名稱 --resource:請artisan幫忙建立一個在
    //                          ./app/Http/Controller底下的Controller，並產生各種(CRUD)函式。
    // 3.檔案-在此新增兩行Route::post('/','ProductController@create');Route::resource('product','ProductController');
    // 4.檔案-到/app/Providers/RouteServiceProvider.php : 在public const HOME = '/home';底下新增一個"protected $namespace = 'ProductController的namespace';"
    //                              意思是告訴RouteServiceProvider.php，我們現在有要使用ProductController，然後它在哪個位置。
    // 5.終端-composer dump-autoload:請server重新讀取新的檔案
    // 6.終端-php artisan route:list : 請終端靠訴我們目前有哪些路由，確認是否成功
    // 7.例如-如果路由仍然失敗，則去RouteServiceProvider.php中的以下兩個地方Route::middleware('api')、Route::middleware('web')的下面，
    //        各新增一個->namespace($this->namespace)即可解決問題，原因是因版本關係namespace被移除，所以要手動加回去。

// 參考網址
        // 流程操作 6. : https://github.com/laravel/laravel/commit/4a6229aa654faae58f8ea627f4b771351692508c?fbclid=IwAR32UUN3RKPDOSuZlwgMz0DwuQ4PQ-3y8J-20d6IwErQPNwnYuNdfaz0kcE

// // 進階路由-group、prefix

// Route::group([
//     // middleware-檢查，是指所有程式碼與API的中間人，以此處為例：要進到index或者print之前，要先在middleware檢查IP是否合格
//     'middleware' => ['checkValidIp'],
//     // prefix-前綴，是指當有人要進入/index或者print時，會變成/web/index或者/web/print
//     // 例如說有些路由是給Adim用的或者VIP用的，可以在這裡設定檢查身分
//     'prefix'=>'web',
//     // namespace-檔案地址,此處的'Web'對應到的是app/Http/Controller/Web
//     'namespace'=>'Web',
// ],function(){
//     // 放在一起的路由，此處的兩個路由(Routes)要去用使用Route::group的陣列屬性
//     // 這裡告訴程式說我要到HomeController@index
//     Route::get('/index','HomeController@index');
//     Route::post('/print','HomeController@print');
// });







