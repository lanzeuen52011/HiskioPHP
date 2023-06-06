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
                    // 更新資料有兩種方式，也可以使用update來指定更改某些欄位，可參考3-5.購物車開發(CRUD建造)-B.-23.CRUD的U
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


    // 3-5.購物車開發(CRUD建造)
        // A.購物車的CRUD的R
            // 1.建立一個Migration
                // 終端輸入"php artisan make:migration create_carts_and_cart_items"
            // 2.到database/migrations/2023_06_03_092858_create_cart_and_cart_items.php創造表格，並留存初始檔
                // 程式碼
                    public function up(): void
                    {
                        Schema::create('carts', function (Blueprint $table) {
                            $table->id();
                            $table->timestamps();
                        });
                        Schema::create('cart_items', function (Blueprint $table) {
                            $table->id();
                            $table->foreignId('cart_id');//因資料表cart與資料表cart_items是有相關(一個購物車有很多個購物項目，因此一對多)
                                                        // 的因此需要foreignId
                            $table->foreignId('product_id');//因prodct不可能只有一個人買，因此此處可以轉換成很多人的購物車內擁有同一個項目的產品(一對多)
                            $table->timestamps();
                        });
                    }
                    public function down(): void
                    {
                        Schema::dropIfExists('carts');
                        Schema::dropIfExists('cart_items');
                    }
            // 3.執行php artisan migrate
            // 4.到(ProductController.php)將資料引入與輸出
                    // 程式碼
                    public function index(Request $request)
                    {
                        $data = DB::table('product')->get();
                        return response($data);
                    }
            // 5.php artisan serve -> 使用Postman測試
            // 6.php artisan make:controller CartController --resource
            // 7.(web.php)設定路由
                // 程式碼
                    Route::resource('carts','CartController');
            // 8.(CartController.php)建立購物車(第一次建立成功，包含啥時建立好的時間戳等)
                // 程式碼
                use Illuminate\Support\Facades\DB; //引入DB模塊
                public function index()
                {
                    $cart = DB::table('carts')->get()->first(); //取得資料表carts的第一個
                    // empty是Laravel中的Helper，用來判斷某變數是否為空
                    if(empty($cart)){
                        // 此處使用insert與SQL語法一樣，now()則是當前系統時間
                        DB::table('carts')->insert(['created_at' => now(),'updated_at'=>now()]);
                        
                        // 資料更新後再重新取得資料
                        $cart = DB::table('carts')->get()->first(); 
                    }
                    return response(collect($cart)); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
                }
            // 9.Postman測試
                // 將網址改成 => http://127.0.0.1:8000/carts
                // 因為在web.php的Route::resource('carts','CartController');，因此到CartController的路由是carts。
                // 只會回傳第一次建立的一筆資料，且不會重複建立
            // 10.(CartController.php)新增查看購物清單功能
                // 程式碼
                    public function index()
                    {
                        $cart = DB::table('carts')->get()->first(); //取得資料表carts的第一個
                        // empty是Laravel中的Helper，用來判斷某變數是否為空
                        if(empty($cart)){
                            // 此處使用insert與SQL語法一樣，now()則是當前系統時間
                            DB::table('carts')->insert(['created_at' => now(),'updated_at'=>now()]);
                            
                            // 資料更新後再重新取得資料
                            $cart = DB::table('carts')->get()->first(); 
                        }
                
                        $cart_Items = DB::table('cart_items')->where('cart_id',$cart->id)->get(); // 到cart_items資料表下撈取cart_id=1的資料
                        $cart = collect($cart);
                        $cart['item'] =  collect($cart_Items);
                
                        return response($cart); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
                    }
            // 11.Postman測試
                // http://127.0.0.1:8000/carts

            // 12.將時間從格林威治時間改為台北時間
                // 到/config/app.php將timezone做變更
                // 'timezone' => 'UTC',
                // ↓ 變成以下 ↓
                // 'timezone' => 'Asia/Taipei',
            // 13.回到資料表carts將整列資料刪除
            // 14.Postman測試，使得系統建立正確的時間
        // B.購物車CRUD的CUD
            // 15.購物車新增欄位quantity
                // php artisan make:migration add_quantity_to_cart_items
            // 16.到database/migrations/2023_06_03_182725_add_quantity_to_cart_items.php
                // 程式碼
                    public function up(): void
                    {
                        Schema::table('cart_items', function (Blueprint $table) {
                            $table->integer('quantity')->after('cart_id'); //要求此欄位在cart_id後面ㄇ
                        });
                    }
                    public function down(): void
                    {
                        Schema::table('cart_items', function (Blueprint $table) {
                            $table->dropColumn('quantity'); // 刪除quantity欄位(刪除欄位)
                        });
                    }
            // 17.終端輸入"php artisan migrate"
            // 18.終端輸入"php artisan make:controller CartItemController --resource"
            // 19.到(web.php)設定路由
                // Route::resource('cart_items','CartItemController');
            // 20.到(CartItemController.php) CRUD的C-> Create
                // 程式碼
                public function store(Request $request)
                {
                    // 新增資料
                    $form = $request->all();
                    DB::table('cart_items')->insert(['cart_id' => $form['cart_id'],
                                                'product_id' => $form['product_id'],
                                                'quantity' =>$form['quantity'],
                                                'created_at' => now(),
                                                'updated_at'=>now()]);
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // 21.Postman測試POST
                // 位置：http://127.0.0.1:8000/cart_items
            // 22.到SQL查看資料表cart_items有沒有被新增資料
            // 23.到(CartItemController.php) CRUD的U-> Update
                // 程式碼
                public function update(Request $request, string $id)
                {
                    //更新資料
                    $form = $request->all();
                    DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
                                            ->update(['quantity' =>$form['quantity'],
                                                      'updated_at'=>now()]);
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // 24.Postman測試PUT
                // 位置：http://127.0.0.1:8000/cart_items/1
            // 25.到(CartItemController.php) CRUD的D-> Delete
                // 程式碼
                public function destroy(string $id)
                {
                    //刪除資料
                    DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
                                            ->delete();
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // 26.Postman測試DELETE
                // 位置：http://127.0.0.1:8000/cart_items/1
            


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


// 5.資料庫 
    // 5-1.資料庫：又包含兩種：
        // 放資料的資料庫：一種為單純存放資料的資料庫
        // 資料庫軟體：擁有邏輯功能且能接收指令並將資料組成有意義的資訊，且能自動化的完成工作，例：MySQL、SQL Server等

    // 5-2.資料庫的類型：
        // 概念：實務上使用RMDB搭配NoSQL，來實踐效能優化
        // RMDB - 關聯式資料庫：每筆資料都可以與他資料做關聯性，資料雖較為肥大，但易於維護，且歷史要為悠久。
                // 例：MySQL(主流)、PostgreSQL(地理座標優化)、Oracle(搭配JAVA，金融機關、大型產業)
        // NoSQL - 非關聯式資料庫：儲存資料方式不同，因此執行上比關聯式資料庫快速了許多，但不適合複雜結構。
                // 例：mongoDB(主流)、redis(可參考)、Firebase(提供非關聯式的快速結構)

    // 5-3.資料庫術語：
        // A.Connection - 資料庫連結: 建立相互連結的通道
        // B.Schema、DataBase - 資料庫： 存放資料表的資料庫(小的：Schema、大的：DataBase)
        // C.Table - 資料表：被存放資料庫的資料表，類似Excel表格
        // D.Data row - 資料列：一列資料(不包含標題)
        // E.Query - 搜尋語法：搜尋資料庫的語法，例：SELECT * FROM sakila.actor;
        // F.Dump - 匯出資料庫： 以MySQL Benchwork 為例，可在Server選項中找到Import/Export
        // 匯入操作 ： Server -> Data Import -> Import from Self-Contained File -> New (輸入資料庫名稱) ->
                    //start import -> SCHEMAS按重新整理 -> 

// 6.MySQL
    // 6-1.點選資料表的板手符號後，會看到資料類型等基礎設定
        // PK - Primary Key：主要Key
        // NN - Not Null：此欄不得為空
        // UQ - UNqle：單一性，預防重複值，通常用在應用程式面與資料庫層面
        // AI - Auto Increment：流水號，如若將先前7筆資料刪除，要新增資料時未將先前紀錄清空乾淨，則會產出流水號8
        // Default/Expression ：預設值

    // 6-2.資料類型
        // 1.INT：基本上可放得下天文數字
        // 2.VARCHAR(N)：拿來放N個字的文本，容易有放不下的BUG出現
        // 3.DATETIME：變成日期格式
        // 4.DOUBLE、FLOAT：小數位數的差別，不精確
        // 5.DECIMAL：精確的小數(較為推薦)
        // 6.BIGINT：比INT還能容納更大的天文數字
        // 7.TINYINT：超級小的INT，例如布林值可設計成TINYINT(1)，以此來存放1與0。
        // 8.TEXT：比較多的文本可使用TEXT來儲存，相較於VARCHAR(N)來說大了很多
        // 9.其他的：JSON、BOOLEAN

    // 6-3.MySQL基礎語法   
        // A.Select - 選擇資料
            // 例句白話文翻譯： 選擇 全部的資料 來自 sampledatabase.sbl_team_data;
            // 例句：SELECT * FROM sampledatabase.sbl_team_data;

            // a.改變將資料表設成預設
                // 解析：對於sbl_team_data的資料庫sampledatabase點右鍵選擇Set as Default Schema。
                // 改變前例句：SELECT * FROM sampledatabase.sbl_team_data;
                //  ↓  會變成以下  ↓
                // 改變後例句：SELECT * FROM sbl_team_data;

            // b.特定欄位選擇
                // 解析：其中的'*'為全部資料，若是只想要看到id欄位與win欄位，則將其替代成id,win即可。
                // 改變前例句：SELECT * FROM sampledatabase.sbl_team_data;
                //  ↓  會變成以下  ↓
                // 改變後例句：SELECT id,win FROM sampledatabase.sbl_team_data;

        // B.Where - 撈出特定資料
            // a.只撈出特定資料
                // 解析：where語法跟Collection的用法一樣，Keys底下的值須等於多少。

                // team_id底下的Value = 2。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where team_id = 2;

                // team_id底下的Value > 2。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where team_id > 2;

                // team_id底下的Value != 2。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where team_id != 2;

                // team_id底下的Value = 2和 Value = 6。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where team_id in(2,6);

                // team_id底下的在Value = 2和5之間。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where team_id between 2 and 5;

                // 在season底下找出包含S1的資料
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where season like '%S1%';

                // 在win底下找出2開頭的資料
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where win like '2%';

                // 在win底下找出2結尾的資料
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where win like '%2';

                // 在lost底下找出空資料(null)資料
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where lost is null;

                // 在lost底下找出不是空資料(null)資料，以及season='S16'的資料
                // 例句：SELECT * FROM sampledatabase.sbl_team_data where lost is not null and season = 'S16';
            
        // C.Insert - 新增資料
            // 表格上直接點兩下都可以輸入資料，按下Apply，會看到新增資料的實際code，如以下：
            // 增加效率的方法：將第二行開始的('12', '3')移動至第一行，並用逗號分隔即可，
            //                  雖然會使得第一行跑比較久，但總效益而言是提升速度的。   

            // 改變前例句：INSERT INTO `sampledatabase`.`sbl_team_data` (`team_id`, `season`) VALUES ('123', '2');
            //            INSERT INTO `sampledatabase`.`sbl_team_data` (`team_id`, `season`) VALUES ('12', '3');

            //  ↓  會變成以下 ↓

            // 改變後例句：INSERT INTO `sampledatabase`.`sbl_team_data` (`team_id`, `season`) VALUES ('123', '2'),('12', '3');

        // D.Update - 更新資料
            // 例句：UPDATE `sampledatabase`.`sbl_team_data` SET `season` = '2' WHERE (`id` = '690');
            // 解析：更新season欄位變成'2'到id是'690'那格。

            // 同時更改id是693、694的資料
            // 例句：UPDATE `sampledatabase`.`sbl_team_data` SET `season` = '2' WHERE (`id` in ('693','694'));
        
        // E.Delete - 刪除資料
            // 例句：DELETE FROM `sampledatabase`.`sbl_team_data` WHERE (`id` = '674');
            //      DELETE FROM `sampledatabase`.`sbl_team_data` WHERE (`id` = '690');

    // 6-4.資料庫關聯原理(Relation)
        // 1.資料模型(Data Model)
            // A.1對1(one to one)：某資料表的單筆值對應某資料表的單筆值，例如資料表owner底下的id對應到sbl_team資料表底下的owner_id，兩者皆為不重複值。
            // B.1對多(one to multi)：某資料表的單筆值對應某資料表的多筆值，例如資料表sbl_team的id對應到資料表sbl_team_data底下的team_id。
            // C.多對多(multi to multi)：
                // a.需要中間資料表sbl_team_player來當資料關聯的轉寄站
                // b.資料表player的唯一值id對應到中間資料表sbl_team_player的多筆值player_id
                // c.資料表sbl_team的唯一值id對應到中間資料表sbl_team_player的多筆值team_id

        // 2.join語法，透過一個Query撈取兩個資料表
            // A.inner join
                // 解析：選擇 全部的資料 來自sampledatabase.sbl_team_data 並且加入 sampledatabase.sbl_teams 
                //      ，以sbl_team_data.team_id與sbl_teams.id的值做相關聯，on之後的左右兩邊的空表格都會被看到。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data (inner，可不存在) join sampledatabase.sbl_teams on sbl_team_data.team_id = sbl_teams.id;
            
            // B.left join
                // 解析：以on裡面左方資料表格為主，如果左邊資料有多的空資料，照樣顯示，以下例句為例，則以sbl_team_data為主。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data left join sampledatabase.sbl_teams on sbl_team_data.team_id = sbl_teams.id;
            
            // C.right join
                // 解析：以on裡面右方資料表格為主，如果右邊資料有多的空資料，照樣顯示，以下例句為例，則以sbl_teams為主。
                // 例句：SELECT * FROM sampledatabase.sbl_team_data right join sampledatabase.sbl_teams on sbl_team_data.team_id = sbl_teams.id;
            
    // 6-5.限制輸入(檢驗輸入的資料)
        // 流程：打開資料表的板手 -> 下方有個工具列找到Foreign Keys -> Foreign Keys命名 -> 選擇一個Reference Table ->
            //  到Column將選擇的表格名稱打勾Reference Table所對應到的表格 -> Apply -> 找個測驗表格實驗。
        // 此處為將sbl_team_data的team_id與sbl_teams的id相互連結呼應，因此sbl_team_data所使用的team_id值會與sbl_teams的id一模一樣
        // Foreign Keys命名方式：資料表名稱_表格名稱，例：sbl_team_data_sbl_team_id
        // 到Column將選擇的表格名稱打勾：此處為team_id

        // 找個測驗表格實驗：在sbl_team_data的team_id輸入10，然後按Apply去執行會跑出Error，因為sbl_teams的id沒有10。


// 7.串接資料庫(Laravel串接MySQL)
    // 7-1.參數設定
        // 只要是關於參數設定的基本上都是在 .env 內去做設定。
        // 處理流程： 進到.env檔案 -> 先找到此次對應的程式參數在哪(此次為MySQL)"DB_CONNECTION=mysql" -> 在
                //   修改底下的參數(輸入登入的密碼) -> 把資料庫名稱貼到DB_DATABASE的欄位 -> 終端跑php artisan serve ->
                //   並將資料庫資料引入PHP中，"資料引入(ProductController)" -> 使用Postman來測試GET能否取得資料(#失敗) ->
                // 

        // 資料引入(ProductController)
            use Illuminate\Support\Facades\DB; // 引用DB功能

            public function index(Request $request)
            {
                $data = DB::table('sbl_teams'); //將MySQL的資料引入
                dump($data);
                return response($data); // 並回傳到網頁上
            }

        // #失敗 could not find driver
            // 1.到php.ini將extension=pdo_mysql 的註解取消掉
            // 2.輸入 composer dump-autoload 使server重新讀取新的檔案，即可

    // 7-2.其他資料庫的參數設定
        // config/database.php -> 'default' => env('DB_CONNECTION', 'mysql')調整，或者下面都有各個資料庫都可以使用。

// 8.Migration 牽動、遷移(重要、必備)
    // 用途：來記錄資料庫做了那些變動，類似git

    // 1.(.env)
        // DB_DATABASE=laravel_demo

    // 2.(MySQL)新增"laravel_demo"
    // 流程：右鍵，Create Schema -> 輸入資料庫名稱，Charset:utf8mb4,Collation:utf8mb4_unicode_ci -> 若有打開php artisan serve，
        //   先將其關閉 -> 終端輸入"php artisan make:migration 資料表名稱" -> 到/database/migration，底下會有一個資料表名稱的檔案 ->
        //   #程式碼設定 -> 終端輸入"php artisan migrate" -> 到資料庫找剛剛的輸入的資料庫名稱，可以找到剛剛輸入的資料表名稱

        // 選擇"Charset:utf8mb4,Collation:utf8mb4_unicode_ci"是因為可以接受特殊字符。

        // #程式碼設定
            // up()通常的更動都會在up內，更動
                public function up(): void
                {
                    Schema::create('products', function (Blueprint $table) { //在products資料表中設定
                        $table->id(); // 自動設定ID
                        $table->string('title'); // 設定欄位 ，string -> VARCHAR(255)
                        $table->string('content'); // 設定欄位 ，string -> VARCHAR(255)
                        $table->integer('price'); // 設定欄位 ，integer -> INT
                        $table->integer('quantity'); // 設定欄位 ，integer -> INT
                        $table->timestamps(); // 以時間戳的方式產生兩個欄位：Create Date、Update Date ，timestamps-> TIMESTAMP

                        // Migration資料型態(Migrate資料型態、Migration datatype、Migrate datatype)參考：
                        // https://laravel.com/docs/10.x/migrations#available-column-types
                    });
                }
            // down()回傳資料庫狀態，復原
                public function down(): void
                {
                    Schema::dropIfExists('products'); //如果'products'資料表存在就刪除掉
                }
            
        // php artisan migrate，執行Migrate
            // 可以到 (.env)找到"DB_DATABASE=資料庫"的資料庫中找底下的資料表，會有migrations的資料表，
            // 如若在執行一次"php artisan migrate"，則會執行migrations資料表以外的migrations。
        
        // php artisan migrate:rollback --step=1，刪除最後一個migration

// 9.Query Builder 進階使用技巧
    // 9-1.新增篩選條件
        // 1.(ProductController.php)
            public function index(Request $request)
            {
                $data = DB::table('sbl_team_data')->select('win');
                $data = $data->addSelect('season')->get(); // 新增篩選條件，並GET
                return response($data); // 並回傳到網頁上
            }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product

    // 9-2.whereRaw將()內的字串變成SQL的where語法
        // 1.(ProductController.php)
            // 程式碼
            public function index(Request $request)
            {
                $data = DB::table('sbl_team_data')->whereRaw('win > lost')->get(); // 將()內的字串變成SQL的where語法
                return response($data); // 並回傳到網頁上
            }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product

    // 9-3.加入其他表格的資料1
        // 1.(ProductController.php)
            // 程式碼
            public function index(Request $request)
            {
                $data = DB::table('sbl_team_data')
                ->join('sbl_teams','sbl_teams.id','=','sbl_team_data.team_id')
                // ->leftJoin('sbl_teams','sbl_teams.id','=','sbl_team_data.team_id')
                // ->rightJoin('sbl_teams','sbl_teams.id','=','sbl_team_data.team_id')
                ->select('*')
                ->get(); 
                return response($data); // 並回傳到網頁上
            }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product
    // 9-4.加入其他表格的資料2
        // 1.(ProductController.php)
            // 程式碼
            // public function index(Request $request)
            // {
            //     // 9-4.加入其他表格的資料2
            //     $data = DB::table('sbl_team_data')
            //     ->join('sbl_teams',function($join){
            //         $join->on('sbl_teams.id','=','sbl_team_data.team_id') //就是SQL語法的join後面的on條件(哪個值與哪個值是相同的)
            //             ->where('sbl_teams.total_win','>','200');
            //             })
            //             ->select('*')
            //             ->get();
            //     return response($data); // 並回傳到網頁上
            // }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product
    // 9-5.加入資料時回傳當筆資料的ID
        // 1.(ProductController.php)
            // 程式碼
            public function index(Request $request)
            {
                $data = DB::table('owner')->insertGetId(['team_id'=>2]); // 新增資料後，能夠馬上得到此筆資料的ID
                return response($data); // 並回傳到網頁上
            }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product

    // 9-6.enableQueryLog會記錄跑了甚麼程式碼
        // 1.(ProductController.php)
            // 程式碼
            public function index(Request $request)
            {
                DB::enableQueryLog(); // 會記錄跑了甚麼程式碼
                $data = DB::table('owner')->insertGetId(['team_id'=>2]); // 新增資料後，能夠馬上得到此筆資料的ID
                // $data = DB::table('owner')->where('team_id',2)->dump(); // 可以看到SQL的程式碼
                // $data = DB::table('sbl_team_data')->where('id',532)->increment('win',2000); // 指定win欄位的值增加2000
                // $data = DB::table('sbl_team_data')->where('id',532)->decrement('win',2000); // 指定win欄位的值減少2000
                dd(DB::getQueryLog()); //跑到這裡中斷
                return response($data); // 並回傳到網頁上
            }
        // 2.Postman測試GET
            // 位置：http://127.0.0.1:8000/product

// 10.Laravel補充說明
    // 10-1.Request生命週期
        // public/index.php
        $app = require_once __DIR__.'/../bootstrap/app.php'; 
        // Laravel本身的應用程式，內含有Laravel本身啟動的組件，bootstrap中也有

        $kernel = $app->make(Kernel::class); 
        //讓程式啟動並跑Http相關的功能，Kernel是設定各種屬性值(例：middleware、TrustProxies、HandleCORS等)，Kernel內延伸的HttpKernel才是Kernel的精華，會有各種屬性跟函式。
        // app/Http/Kernel是app/console/Kernel內的consoleKernel

        $response = $kernel->handle(
            $request = Request::capture() //捕獲Http Request 
        )->send();

        $kernel->terminate($request, $response);


    // 10-2.所有 Request 的集中站 - Middleware 介紹
        // 從app/Http/Kernel.php@$middlewareAliases，可以找到Middleware的使用方法，Middleware就是寫在以下的'middleware' => ['checkValidIp'],這行
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

    // 10-3.如何製作 Middleware
        // I.終端輸入php artisan make:middleware CheckDirtyWord
        // II.app/Http/Middleware 設定如何驗證
            // 程式碼
            public function handle(Request $request, Closure $next): Response
            {
                $dirtyWords  = [ //可能的髒字(?)
                    'apple',
                    'orange'
                ];
                $parameters  = $request->all(); // 前端傳來的資料
                foreach($parameters as $key => $value){
                    if($key == 'content'){
                        // 每個是content的內容就判斷
                        foreach($dirtyWords as $dirtyWord){
                            // 一個一個字判斷
                            if(strpos($value,$dirtyWord) !== false){ //切記不可用!=，必須得用!==，因若是返回值為Index 0，會被判斷成false，因此必須為!==。
                            //使用strpos來判斷$value有沒有包含$dirtyWords，回傳包含$dirtyWords的第幾個引數開始，沒有則反為false
                                return response('dirty',400);
                            }
                        }
                    }
                }
                return $next($request);
            }

        // III. app/Http/Kernel.php 新增驗證方式
            // 程式碼
                protected $middlewareAliases = [
                    'auth' => \App\Http\Middleware\Authenticate::class,
                    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
                    'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
                    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
                    'can' => \Illuminate\Auth\Middleware\Authorize::class,
                    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
                    'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
                    'signed' => \App\Http\Middleware\ValidateSignature::class,
                    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
                    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                    'check.dirty' => \App\Http\Middleware\CheckDirtyWord::class, //新增此行
                ];

        // IV.web.php 設定中繼站與路由
            // 程式碼
                Route::group(['middleware'=>'check.dirty'],function(){
                    Route::resource('product','ProductController');
                });
        // V.Postman測試POST
            // 位置：http://127.0.0.1:8000/product
            // Body:title:cool,content:apple
            // 回傳'dirty'或者設定好的其他回答，則是成功，若content並非設定好的dirtywords，則會回傳該回傳的資料。


            
















