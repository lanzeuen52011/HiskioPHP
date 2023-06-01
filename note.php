<?php
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
    //              Route::resource('product','ProductController');
        







// 1.web.php - 路由流程操作-路由設定
    // 1.終端-php artisan route:list : 請終端靠訴我們目前有哪些路由
    // 2.終端-php artisan make:controller Controller名稱 --resource:請artisan幫忙建立一個在
    //                          ./app/Http/Controller底下的Controller，並產生各種(CRUD)函式。
    // 3.檔案-在web.php新增兩行Route::post('/','ProductController@create');Route::resource('product','ProductController');
    // 4.檔案-到/app/Providers/RouteServiceProvider.php : 在public const HOME = '/home';底下新增一個"protected $namespace = 'ProductController的namespace';"
    //                              意思是告訴RouteServiceProvider.php，我們現在有要使用ProductController，然後它在哪個位置。
    // 5.終端-composer dump-autoload:請server重新讀取新的檔案
    // 6.終端-php artisan route:list : 請終端靠訴我們目前有哪些路由，確認是否成功
    // 7.例如-如果路由仍然失敗，則去RouteServiceProvider.php中的以下兩個地方Route::middleware('api')、Route::middleware('web')的下面，
    //        各新增一個->namespace($this->namespace)即可解決問題，原因是因版本關係namespace被移除，所以要手動加回去。
    // 參考網址
        // 流程操作 6. : https://github.com/laravel/laravel/commit/4a6229aa654faae58f8ea627f4b771351692508c?fbclid=IwAR32UUN3RKPDOSuZlwgMz0DwuQ4PQ-3y8J-20d6IwErQPNwnYuNdfaz0kcE


// 2.web.php - 路由進階 - group、prefix
    Route::group([
        // middleware-檢查，是指所有程式碼與API的中間人，以此處為例：要進到index或者print之前，要先在middleware檢查IP是否合格
        'middleware' => ['checkValidIp'],
        // prefix-前綴，是指當有人要進入/index或者print時，會變成/web/index或者/web/print
        // 例如說有些路由是給Adim用的或者VIP用的，可以在這裡設定檢查身分
        'prefix'=>'web',
        // namespace-檔案地址,此處的'Web'對應到的是app/Http/Controller/Web
        'namespace'=>'Web',
    ],function(){
        // 放在一起的路由，此處的兩個路由(Routes)要去用使用Route::group的陣列屬性
        // 這裡告訴程式說我要到HomeController@index
        Route::get('/index','HomeController@index');
        Route::post('/print','HomeController@print');
    });

// 3.ProductController - API開發
    // 3-1.網站資料傳輸基礎 Request & Response
        // ProductController.php
            public function index(Request $request)
            {
                //當達到URI的時候，可以指定URI來指定執行此函式
                dump($request);
                // dump解析：
                    //dump();類似var_dump();，此處dump執行方式為先使用"php artisan route:list"，
                    // 找到從哪個路由會執行此函式index()，再使用"php artisan serve"的網址列加上路由，
                    // 以此處為例：http://127.0.0.1:8000/product
                    // 把網址變成http://127.0.0.1:8000/product?name=John，會在query的參數找到包含John的陣列
                    // server，會有PHP版本路徑IP等的資訊內容
                    // cookies，可以拿來放暫存的資料，如廣告等等
                    // headers，來表示request的屬性

                //獲取的是整個 HTTP 請求中的所有數據，包括表單提交的數據、URL 中的查詢參數、文件上傳等並返回一個關聯數組，'name'=>John，但資料會顯示在網址列上
                dump($request->all()); 

                //URL中的查詢參數 ? 開頭的部分傳遞的參數並返回一個關聯數組，'name'=>John，資料不會顯示在網址列上
                dump($request->query()); 

                //會得到$request['query']的參數，但只取得'name'的值，也就是'John'
                dump($request->input('name')); 

                //會得到$request['query']的參數，但只取得'age'的值，但此處沒有age為key的資料，回傳NULL
                dump($request->input('age')); 

                //'age'的值使用者沒有回傳的話，會帶入預設值10
                dump($request->input('age',10)); 

                //會得到根目錄後的網址
                dump($request->path()); 

                // 此處的welcome就是在web.php中的return view('welcome');
                // return response()->view('welcome');

                // response(內容,連線狀態);其中連線狀態常回傳 200(連線成功)、400(資料有誤)、500(伺服器壞掉了)，要更多的狀態碼，可以查詢Http status code
                // return response('123',200); 

                // 導頁，就是自動換頁，此處就是輸入http://127.0.0.1:8000/product 會直接被打回 http://127.0.0.1:8000/
                return redirect('/'); 
            }
    // 3-2.Read讀取資料(GET)
    class ProductController extends Controller
    {
        public function index(Request $request)
        {    
            // 利用$this來指回ProductController類，在往下使用創立的method getData();，並取得資料，然後裝到$data中
            $data=$this->getData(); 
            return response($data); // 並將$data return response 給客戶端(前端)
        }

        public function getData(){
            // 當使用此函式時，return資料。
            return[
                [
                    'title'=>'測試商品一',
                    'content'=>'這是很棒的商品',
                    'price'=> 50
                ],
                [
                    'title'=>'測試商品二',
                    'content'=>'這是有點棒的商品',
                    'price'=> 30
                ],
            ];
        }
    }
    // 3-3.Create 更新資料(POST)
        // 1.搜尋VerifyCsrfToken.php，位置在app/Http/Middleware
        // CsrfToken:拿來檢測前端回傳的密碼是否合法
        // (VerifyCsrfToken.php)
            protected $except = [
                //拿來檢測前端回傳的密碼是否合法
                '*' //對於資料回傳一律不檢查，正式資料庫絕對不可以這麼做唷！
            ];

        // web.php
        // Route::post('/','ProductController@create'); ，於此課程中此行需註解掉

        // (ProductController.php)
            // public function create(){} //使用GET，以表單為例，此為表單問題生成處
            // public function store(Request $request){} //使用POST，以表單為例，此為表單回答接收端
            public function store(Request $request) //使用POST
            {
                //Request，屬於Laravel內建的物件，用來檢查參數的格式與資料類別
                $data = $this->getData(); //使用getData函數並裝到$data內
                $newData = $request->all(); //把request的資料抓出來並裝到$newData內
                array_push($data,$newData); //把$newData push到$data內
                return response($data); //回傳$data
        
            }
        
        // Postman.exe
            // 使用網址到Product，並且將資料POST回來，並查看Pretty的部分，資料很像是以json格式即可。

    // 3-4.Update + Delete，更新與刪除資料
        // Update 更新資料
            // (ProductController.php)
            // 1.將資料加上id
                public function getData(){
                    return collect([
                        collect([
                            'id'=> 0 , //此處是拿來放在/product/{product}中的{product}
                            'title'=>'測試商品一',
                            'content'=>'這是很棒的商品',
                            'price'=> 50
                        ]),
                        collect([
                            'id'=> 1 ,
                            'title'=>'測試商品二',
                            'content'=>'這是有點棒的商品',
                            'price'=> 30
                        ]),
                    ]);
                }
            // Postman.exe
                // 2.使用PUT修改id:0的 => http://127.0.0.1:8000/product/0
                // 3.Body-> x-www-form-urlencoded

            // (ProductController.php)
                public function update(Request $request, string $id)
                {
                    // dd($request->all()); // dd類似於dump，但是與dump不同的是，dd後面的dump是不會被執行的，dd只會執行到dd這行
                    // dump(321);
                    $form = $request -> all();
                    $data = $this->getData();
            
                    // where(keys,value=$id):找資料
                    $selectedData = $data->where('id',$id)->first(); // 使用first來取得第一筆資料
                    $selectedData=$selectedData->merge(collect($form)); // 將$data的第一個取代成$form，此處為字典陣列，因此資料會被取代
            
                    return response($selectedData); // 將更新完的資料回傳
                }

        // Delete 刪除資料
            // 1.由路由器可得知一樣是product/{product}來刪除，其中{product}為$id

            public function destroy(string $id)
            {
                $data = $this->getData();
                $data = $data->filter(function($product)use($id){ //此處必須使用use將$id帶入函式中
                    return $product['id'] != $id;
                });
                // return response($data); //回傳剩下的資料
                return response($data->values()); //回傳剩下的資料的值
            }

            // 2.Postman選用Delete，然後把網址加上id實驗即可
                // 尾處加上$id的網址：http://127.0.0.1:8000/product/0
    





// 4.認識 Laravel 主資料架構 - Collection
    // 概念：提供流暢、便利的封裝來控制陣列資料
    // Why Collection:因PHP的函式不方便於複雜環境使用，因此Laravel-Collection提供更簡單且語法向SQL的方式在複雜的環境下操作陣列
    //  Collection-各種方法的參考資料：https://laravel.tw/docs/5.2/collections 

    public function getData(){
        // Collection型式，注意其效果在index需使用dump來呈現，若使用return會看不出差別，並去Postman使用GET方法底下的Preview來觀測。
        

        // 非collection函式操作
            // array_push($data,$newData); //把$newData push到$data內

        // collection形式函式操作
            // $data->push(collect($newData));
            // dump($data);


        // Collection陣列
            return collect([
                collect([
                    'title'=>'測試商品一',
                    'content'=>'這是很棒的商品',
                    'price'=> 50
                ]),
                collect([
                    'title'=>'測試商品二',
                    'content'=>'這是有點棒的商品',
                    'price'=> 30
                ]),
            ]);

        // 非Collection陣列
            // return[
            //     [
            //         'title'=>'測試商品一',
            //         'content'=>'這是很棒的商品',
            //         'price'=> 50
            //     ],
            //     [
            //         'title'=>'測試商品二',
            //         'content'=>'這是有點棒的商品',
            //         'price'=> 30
            //     ],
            // ];
    }












