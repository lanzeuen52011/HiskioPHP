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

    // 3-6.會員註冊 - Auth 套件與相關概念
        // A.會員註冊
        // 此處會使用到Laravel官方套件Passport，驗證授權的套件
        // Passport官方文件：https://laravel.com/docs/10.x/passport
            // 1.終端輸入"composer require laravel/passport"安裝passport，
                // 如遇到"Installation failed, reverting ./composer.json and ./composer.lock to their original content."，
                // 刪除composer.json與composer.lock，然後重新輸入指令即可。
            // 2.終端輸入"php artisan migrate"，因為安裝完以上後他會偷偷的裝上一些table(資料表)
                // 如果遇到PHP Fatal error:  Uncaught Error: Class "Illuminate\Foundation\Application" not found in C:\Users\lanze\Desktop\php-trainning\Hiskio\blog\bootstrap\app.php:14
                    // i.請將composer.json恢復到最一開始專案成立時的樣子
                    // ii.刪除vendor資料夾
                    // iii.再終端輸入"composer clear-cache"，將快取清除
                    // iv.在composer.json中，將 "laravel/passport": "^11.8" 添加到 "require" 部分
                    // v.終端輸入"composer instal"
                    // vi.終端輸入"php artisan migrate"，即可。
            // 3.終端輸入"php artisan passport:install"，產生必要的Class，與加密密碼(用於API的通行證，也就是所謂的API Key)
            // 4.到(app/Model/User.php)加上API Key的模組
                use Laravel\Passport\HasApiTokens; //trait類型為工具箱類型，可使用多種功能
            // 5.到(config/auth.php)，使用API的方式登入，所以改成passport
                'guards' => [
                    'web' => [
                        'driver' => 'session',
                        'provider' => 'users',
                    ],
                
                    'api' => [
                        'driver' => 'passport', // 使用API的方式登入，所以改成passport
                        'provider' => 'users',
                    ],
                ],
            // 6.終端輸入"php artisan make:controller AuthController"
            // 7.到(app/Http/Controller)
                public function signup(Request $request){
                    // 這裡是拿來建立帳號密碼的，前端傳資料過來，建立會員的帳號密碼
                    $form = $request->all();
                }
            // 8.終端輸入"php artisan make:request CreateUser"
            // 9.到(app/Http/Request/CreateUser.php)註冊帳號時的檢驗
                class CreateUser extends APIRequest{// 使用APIRequest來檢查
                        public function authorize(): bool
                    {
                        return true; //先改成true，讓它通過即可，因現在還用不到此功能
                    }
                    public function rules(): array
                    {
                        return [
                            'name'=>'required|string',
                            'email'=>'required|string|email|uniqle:users', 
                            //uniqle:users，使用者單一性，此處指每個在users資料表中的使用者的email必須具備唯一性，僅限單對單
                            'password'=>'required|string|confirmed', 
                            // confirmed，此處會使得使用者做重複確認，就是註冊時"確認密碼"的欄位，因此前端回傳的password與password_confirmation必須一致
                            
                
                        ];
                    }
                } 
            // 10.到(AuthController.php)來使用(CreateUser.php)的Request
                use App\Http\Requests\CreateUser;
                public function signup(CreateUser $request){
                    // 這裡是拿來建立帳號密碼的，前端傳資料過來，建立會員的帳號密碼
                    $validatedData = $request->validated();  // 使用validated來呼叫資料，因為驗證通過所以可以呼叫了
                    $user = new User([
                        'name'=>$validatedData['name'],
                        'email'=>$validatedData['email'],
                        'password'=>bcrypt($validatedData['password']), 
                        // 'password'=>$validatedData['password']會直接顯示密碼，因此須加入bcrypt('123456')來保護密碼
                        // 沒bcrypt的話，打123456，會直接跑出123456
                        // 使用加密函式，bcrypt('123456')，會跑出$2y$10$AKwOUXmULly7Uc2Nsrdwqul8j.GjK6xKrXfYI2McJqiHh1cMb7/dm，以此來保護密碼
                    ]);
                    $user->save(); // 將資料儲存
                    return response('success',201);
                }
            // 11.到web.php設定路由
                Route::post('signup','AuthController@signup');
            // 12.Postman測試POST
                // 位置：http://127.0.0.1:8000/signup
    // 3-7.會員登入 - 會員登入功能製作
        // 概念：把帳號跟密碼輸入給Laravel，然後利用auth與Passport相關套件，來解析是哪位User並產生通行證(Token)，
        //       然後將通行證(Token)轉交給客戶端，每使用任何一項服務都要檢查一次通行證，並且是不可過期的。
            // 1.到(AuthController.php)做出基本的檢驗與錯誤的帳號密碼回傳
                use Illuminate\Support\Facades\Auth;
                public function login(Request $request){ // 此處指單純接使用者回傳的資料，並沒有要做格式驗證
                    $validatedData = $request->validate([ // 基礎資料驗證，此處的用意為表格email、password欄位不得為空
                        'email'=>'required|string|email',
                        'password'=>'required|string',
                    ]);
                    if(!Auth::attempt($validatedData)){ // attempt為，直接將回傳的帳號密碼拿去登入，因此此處為"如果登入失敗"
                        return response('授權失敗',401);
                    }
                }
            // 2.(web.php)建立路由
                Route::post('login','AuthController@login');
            // 3.Postman測試POST錯誤的帳號密碼是否正確回傳授權失敗
                // 位置：http://127.0.0.1:8000/login
            // 4.到(AuthController.php)新增登入成功的程式碼
                public function login(Request $request){ // 此處指單純接使用者回傳的資料，並沒有要做格式驗證
                    $validatedData = $request->validate([ // 基礎資料驗證，此處的用意為表格email、password欄位不得為空
                        'email'=>'required|string|email',
                        'password'=>'required|string',
                    ]);
                    if(!Auth::attempt($validatedData)){ // attempt為，直接將回傳的帳號密碼拿去登入，因此此處為"如果登入失敗"
                        return response('授權失敗',401);
                    }
                    // 授權通過後，user的資料會被放進$request，因此使用$request->user()來撈取user的資料。
                    $user = $request->user();
                    dd($user);
                }
            // 5.Postman測試POST 是否正確回傳
                // 位置：http://127.0.0.1:8000/login
            // 6.到(AuthController.php)新增登入成功後的通行證(Token)
                public function login(Request $request){ // 此處指單純接使用者回傳的資料，並沒有要做格式驗證
                    $validatedData = $request->validate([ // 基礎資料驗證，此處的用意為表格email、password欄位不得為空
                        'email'=>'required|string|email',
                        'password'=>'required|string',
                    ]);
                    if(!Auth::attempt($validatedData)){ // attempt為，直接將回傳的帳號密碼拿去登入，因此此處為"如果登入失敗"
                        return response('授權失敗',401);
                    }
                    // 授權通過後，user的資料會被放進$request，因此使用$request->user()來撈取user的資料。
                    $user = $request->user();
                    $tokenResult = $user->createToken('Token'); 
                    //此處的createToken函式是來自於app/Http/Model/User內的use Laravel\Passport\HasApiTokens;
                    $tokenResult->token->save();
                    // 此處將token儲存，以利後續操作與驗證授權
                    return response(['token' => $tokenResult->accessToken]);
                }
            // 7.Postman測試POST，看登入成功後，會不會把accessToken印出來(確認回傳正常，代表會員登入功能已完成)
                // 位置：http://127.0.0.1:8000/login
                // 參考網站：https://jwt.io/
                    // 可將accessToken進行解析的網站。
                    // 操作流程：進入網站 -> Encoded -> 將accessToken貼到文字區域內
                    // 介紹：
                        // typ：資料類型
                        // alg：演算法
            // 8.到SQL的資料表oauth_access_token，可以看到TOKEN的相關資訊
    // 3-8.會員登出 Auth 套件應用 - 登出與獲取使用者資料和進階路由概念
        // 如何讓通行證(Token)過期，不讓此通行證繼續使用。
            // 1.到(AuthController.php)，建立受保護的API端點
                public function user(Request $request){
                    return response(
                        $request->user() // user的資料
                    );
                }
            // 2.web.php建立路由
                Route::group([
                    'middleware'=> 'auth:api'
                ],function(){
                    Route::get('user','AuthController@user');
                });
            // 3.Postman測試，確認可依照憑證拿到user資料(確認Token的有效性)
                // 位置：http://127.0.0.1:8000/user
                // 先取得前次的accessToken，選到Authorization -> Type，選擇Bearer Type -> 將accessToken的碼貼到Token
            // 4.到(AuthController.php)建立登出並註銷通行證(Token)
                public function logout(Request $request){
                    $request->user()->token()->revoke(); // revoke，讓通行證(Token)失效
                    return response(
                        ['message'=>'成功登出'] // 回傳登出成功
                    );
                }
            // 5.到web.php，新增路由
                Route::group([
                    'middleware'=> 'auth:api'
                ],function(){
                    Route::get('user','AuthController@user');
                    Route::get('logout','AuthController@logout'); // 因登出的人必定是已經登入的狀態，因此需要經過驗證才可以登出。
                });
            // 6.Postman測試是否成功登出並註銷通行證(Token)
                // 切記以下兩個測試時，都要放剛才的通行證到Authorization
                // 登出位置：http://127.0.0.1:8000/logout
                // 通行證是否註銷位置：http://127.0.0.1:8000/user
            // 7.到(/app/Http/Middleware/Authenticate.php)，畢竟通行證(Token)已被註銷，總不能在登入失敗時跑出亂碼，因此要來進行設置
                // 將整個redirectTo的function，註解掉，因為今天用不到
                    // protected function redirectTo(Request $request): ?string // redirectTo，為登入失敗時會訊息來自於此
                    // {
                    //     // return $request->expectsJson() ? null : route('login'); // 導流至login，但今天不需要(but not today，這句英文沒啥意函，只是想這樣打而已)
                    // }
            // 8.到(app/Exceptions/Handler.php)，不管是登出後再度使用通行證(Token)，還是其他的狀況，都會回傳'授權失敗'。
                use Illuminate\Auth\AuthenticationException;
                protected function unauthenticated($request, AuthenticationException $exception)
                {
                    // 這是handler.php原先就有的函式，但在此處是為了要將原本的函式覆寫
                    // 當發現錯誤時，會來執行這裡的程式
                    return response('授權失敗',401);
                }
            // 9.Postman測試GET
                // 位置：http://127.0.0.1:8000/user
    // 3-9.購物車與會員功能整合
            // 1.終端輸入"php artisan make:migration add_user_id_to_carts"，因每個服務與購物車都是不同人，因此要來個別對應
            // 2.到(database/migrations/add_user_id_to_carts)
                public function up(): void
                {
                    Schema::table('carts', function (Blueprint $table) {
                        $table->foreignId('user_id')->constrained('users')->after('id'); // 此處使用foreignId而非integer，是因為要綁定使用者，以防止不存在的人，在使用不存的Cart。
                        // constrained，表示user_id綁定在users內的id欄位
                        // after，使欄位產生在id之後
                    });
                }
                public function down(): void
                {
                    Schema::table('carts', function (Blueprint $table) {
                        $table->dropConstrainedForeignId('user_id');
                        // dropConstrainedForeignId會先將constrained的綁定關係解除，才進行drop(欄位取消)。
                    });
                }
            // 3.SQL中的users的id先改成0
                // 原因：因migration在執行時，會從id:0開始，而目前直接就是id:1，會導致migration執行失敗，因此必須先將id改成0。
            // 4.終端輸入"php artisan migrate"，並到SQL檢查資料表cart，是否有新增user_id欄位，並確認foreign有被正確設置。
            // 5.到(app/Models/Cart.php)
                protected $guarded = ['']; // 此處為黑名單
            // 6.到(web.php)，將放在外面的carts與cart-items的Routes放到group裡面
                Route::group([
                    'middleware'=> 'auth:api'
                ],function(){
                    Route::get('user','AuthController@user');
                    Route::get('logout','AuthController@logout');
                    Route::resource('carts','CartController');
                    Route::resource('cart-items','CartItemController'); // 官方建議使用'-'而非'_'
                });
            // 7.到(CartController.php)
                public function index()
                {
                        $user = auth()->user(); // 透過此函式可拿到已通過認證的user本身的資料。
                        $cart = Cart::with(['cartItems'])
                        // 此處的cartItems為/app/Http/Model/Cart.php的cartItems
                        // with()，Model會自動去尋找Cart相關聯的資料(有建立過關聯的)，並順便撈出來，可解決n+1 cubed的問題
                        //       ，且with會暫存，因此不必下重複的SQL語法來撈取同樣的資料
                        ->where('user_id',$user->id)
                        // 此處用於確認此user的id是否存在，沒有這個id(人)的購物車才須要去增加。
                        ->firstOrCreate(['user_id' => $user->id]);
                    // firstOrCreate()，Model判斷表中有無資料，若無則自動建立
                    return response($cart); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
                }
            // 8.終端輸入"php artisan serve"
            // 9.Postman測試
                // A.先註冊一個新的帳號
                    // 位置：http://127.0.0.1:8000/signup
                // B.登入，並取得新的通行證(Token)
                    // 位置：http://127.0.0.1:8000/login
                // C.建立新Cart資料
                    // 將取得的通行證(Token)放入Authorization的Token中，並選擇Bearer Token。
                    // 位置：http://127.0.0.1:8000/carts
                // D.將上一個動作(C.建立新Cart資料)再執行一次，會取得剛剛建立的cart資料
                // E.測試新增一筆cart-item
                    // 將取得的通行證(Token)放入Authorization的Token中，並選擇Bearer Token。
                    // 位置：http://127.0.0.1:8000/cart-items
                // F.登出
                    // 將取得的通行證(Token)放入Authorization的Token中，並選擇Bearer Token。
                    // 位置：http://127.0.0.1:8000/logout
    // 3-10.購物車結帳功能製作
        // 因為結帳會牽涉到兩個問題，製造訂單與訂單中的商品，就跟Cart跟CartItem的概念很相似
        // A.製作訂單會需要用到的表格欄位，並梳理目前的資料庫模式
            // 1.終端輸入"php artisan make:migrationde create_orrs_and_order_items"
            // 2.到(database/migration/create_orrs_and_order_items.php)
                public function up(): void
                {
                    Schema::create('orders', function (Blueprint $table) {
                        $table->id();
                        $table->foreignId('user_id')->constrained('users'); 
                        // 因為一個user會有多個orders的id，因此是一對多關係，並連結到資料表users。
                        $table->foreignId('cart_id')->constrained('carts'); 
                        // order跟cart有關聯，一個購物車只會對應到一個訂單
                        $table->boolean('is_shipped')->default(0);
                        //  is_shipped欄位，代表著是否被運送，預設是false也就是還沒運送
                        $table->timestamps();
                    });
                    Schema::create('order_items', function (Blueprint $table) {
                        $table->id();
                        $table->foreignId('product_id')->constrained('products'); 
                        $table->foreignId('order_id')->constrained('orders');  // 因為order_items是屬於order底下的附屬，因此需產生連結
                        $table->timestamps();
                    });
                }
                public function down(): void
                {
                    Schema::dropIfExists('orders');
                    Schema::dropIfExists('order_items');
                }
            // 3.終端輸入"php artisan migrate"
        // B.製作Models將原先資料的關聯性都建立好
            // 4.製作model，到(app/Models/)找一個Model複製，並改名成Order.php，並在Model中建立關聯性
                namespace App\Models;

                use Illuminate\Database\Eloquent\Factories\HasFactory;
                use Illuminate\Database\Eloquent\Model;
                
                class Order extends Model
                {
                    use HasFactory;
                    protected $guarded = ['']; // 此處為黑名單
                    public function orderItems(){
                        return $this->hasMany(OrderItem::class); //設定好關聯Order.php的底下有OrderItem
                    }
                    public function user(){
                        return $this->belongsTo(User::class); //設定好關聯Order.php是User的附屬
                    }
                    public function cart(){
                        return $this->belongsTo(Cart::class); //設定好關聯Order.php是Cart的附屬
                    }
                }
            // 5.製作model，到(app/Models/)找一個Model複製，並改名成OrderItem.php，並在Model中建立關聯性
                namespace App\Models;
                
                use Illuminate\Database\Eloquent\Factories\HasFactory;
                use Illuminate\Database\Eloquent\Model;
                
                class OrderItem extends Model
                {
                    use HasFactory;
                    protected $guarded = ['']; // 此處為黑名單
                    public function product(){
                        return $this->belongsTo(Product::class); //設定好Order.php要怎麼去取得OrderItem
                    }
                    public function order(){
                        return $this->belongsTo(Order::class); //設定好Order.php要怎麼去取得User
                    }
                }
            // 6.製作model，到(app/Models/Product.php)
                public function cartItems(){
                    return $this->hasMany(CartItem::class); //設定好Product.php要怎麼去取得CartItem
                }
                public function orderItems(){
                    return $this->hasMany(OrderItem::class); //設定好Product.php要怎麼去取得OrderItems
                }
            // 7.製作model，到(app/Models/Order.php)
                public function cartItems(){
                    return $this->hasMany(CartItem::class); //設定好關聯Cart.php底下有CartItem
                }
                public function user(){
                    return $this->belongsTo(User::class); //設定好關聯Cart.php是User的附屬
                }
                public function order(){
                    return $this->hasOne(Order::class); //設定好關聯Cart.php是底下只會有一個Order
                }
            // 8.終端輸入"php artisan tinker"，先來檢查關聯模組是否都設置正確，然後關閉tinker。
        // C.結帳系統開始製作
            // 9.終端輸入"php artisan make:migration add_checked_to_carts"，開始建立結帳系統
            // 10.到(database/migration/add_checked_to_carts.php)
                public function up(): void
                {
                    Schema::table('carts', function (Blueprint $table) {
                        $table->boolean('checkouted')->default(0)->after('user_id'); 
                    });
                }
                public function down(): void
                {
                    Schema::table('carts', function (Blueprint $table) {
                        $table->dropColumn('checkouted');
                    });
                }
            // 11.終端輸入"php artisan migrate"
        // D.邏輯製作(資料)-資料操作部分可以把邏輯留在Model，因為Model是處理資料面的問題，Controller是處理流程面的問題
            // 12.到(Cart.php)建立checkout函式
                public function checkout(){
                    $order = $this->order()->create([
                        'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
                    ]);
                }
            // 13.終端輸入"php artisan tinker"，並輸入"Cart::find(4)->checkout()"，以此來確認程式碼是正確的，並到SQL的orders確認是否有新產生訂單，並先將新訂單刪除
            // 14.到(Cart.php)
                public function checkout(){
                    $order = $this->order()->create([
                        'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
                    ]);
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $order->orderItems()->create([
                            'product_id'=>$cartItem->product_id,
                            'price' => $cartItem->product->price
                        ]);
                    }
                    $this->update(['checkouted'=>true]);
                    $order->orderItems; // 在order中也要跟著回傳orderItems
                    return $order; // 回傳訂單長甚麼樣子
                }
        // E.邏輯製作(流程)-Controller，因為Model是處理資料面的問題，Controller是處理流程面的問題
            // 15.到(User.php)，使得可以從cart資料可以在auth()->user()中找到 -> auth()->user()->carts()
                public function carts(){
                    return $this->hasMany(Cart::class);
                }
            // 16.到(CartController.php)
                public function checkout(){
                    $user = auth()->user();
                    $cart = $user->carts()->where('checkouted',false)->with('cartItems')->first(); // 取得還未結帳的第一筆資料
                    // 此處撈出cart資料時也會跟著把cartItems資料給撈出來，會變成使用lazyload的方式，就可以少掉下SQL語法的效能，來節省效能。
                    if($cart){
                        $result = $cart->checkout(); // 把$cart抓到的資料來執行結帳(checkout函數)
                        return response($result);
                    }
                    else{
                        return response('沒有購物車',400);
                    }
                }
            // 17.終端輸入"php artisan tinker"，輸入"Cart::find(4)->checkout()"app
                // 檢查資料表order是否有產生訂單
                // 檢查資料表cart_items的cart_id(4)有幾筆訂單，並至order_items是否有cart_items的cart_id(4)底下的訂單轉過來到order_items中
                // 檢查資料表cart，id(4)的資料的checkouted是否從0(fasle)被改成1(true)
            // 18.Controller行為測試-資料清乾淨
                // 將資料表cart，id(4)的資料的checkouted的1改成0
                // 將資料表order_items，id(4)底下的訂單刪除
                // 將檢查資料表order產生的新訂單刪除
            // 19.到(web.php)新增路由
                Route::group([
                    'middleware'=> 'auth:api'
                ],function(){
                    Route::get('user','AuthController@user');
                    Route::get('logout','AuthController@logout');
                    Route::post('carts/checkout','CartController@checkout'); // 此筆為此次新增的
                    Route::resource('carts','CartController');
                    Route::resource('cart-items','CartItemController'); // 官方建議使用'-'而非'_'
                });
            // 20.終端輸入"php artisan serve"
            // 21.Postman測試POST
                // 位置：http://127.0.0.1:8000/carts/checkout
                // 切記要先登入
    // 3-11.新增購物車功能 - 結帳功能優化 + Vip 優惠
        // A.結帳功能優化
            // 1.到(CartController.php)，新增判斷條件，不要撈出已經結帳的購物車，以此來節省效能
                public function index()
                {
                        $user = auth()->user(); // 透過此函式可拿到已通過認證的user本身的資料。
                        $cart = Cart::with(['cartItems'])
                        // 此處的cartItems為/app/Http/Model/Cart.php的cartItems
                        // with()，Model會自動去尋找Cart相關聯的資料(有建立過關聯的)，並順便撈出來，可解決n+1 cubed的問題
                        //       ，且with會暫存，因此不必下重複的SQL語法來撈取同樣的資料
                        ->where('user_id',$user->id)
                        // 此處用於確認此user的id是否存在，沒有這個id(人)的購物車才須要去增加。
                        ->where('checkouted',false)
                        // 新增此判斷條件，不要撈出已經結帳的購物車，以此來節省效能
                        ->firstOrCreate(['user_id' => $user->id]);
                    // firstOrCreate()，Model判斷表中有無資料，若無則自動建立        
                    return response($cart); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
                }
        // B.Vip 優惠
            // 1.終端輸入"php artisan make:migration add_checkout_feature_columns"
            // 2.到(database/migration/add_checkout_feature_columns.php)
                public function up(): void
                {
                    Schema::table('order_items', function (Blueprint $table) {
                        $table->integer('price')->after('order_id'); 
                    });
                    Schema::table('users', function (Blueprint $table) {
                        $table->integer('level')->default(1)->after('id'); 
                    });
                }
                public function down(): void
                {
                    Schema::table('order_items', function (Blueprint $table) {
                        $table->dropColumn('price'); 
                    });
                    Schema::table('users', function (Blueprint $table) {
                        $table->dropColumn('level'); 
                    });
                }
            // 3.終端輸入"php artisan migrate"
            // 4.到(Cart.php)Model
                private $rate = 1; // 費率
                public function checkout(){
                    $order = $this->order()->create([
                        'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
                    ]);
                    if($this->user->level ==2){
                        $this->rate = 0.8; //如果是vip(使用者等級2)，就打八折
                    }
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $order->orderItems()->create([
                            'product_id'=>$cartItem->product_id,
                            'price' => $cartItem->product->price *$this->rate
                        ]);
                    }
                    $this->update(['checkouted'=>true]);
                    $order->orderItems;
                    return $order; // 回傳訂單長甚麼樣子
                }
            // 5.終端輸入"php artisan serve"，並到SQL把資料表user的level從1改成2
            // 6.Postman測試POST，查看價格有無跟著折扣
                // 位置：http://127.0.0.1:8000/carts/checkout
    // 3-12.新增購物車功能 - 商品數量檢查，邏輯防呆
        // A.在放入購物車時就要先告訴使用者商品不足
            // 1.到(CartitemController.php)，新增擴充商品數量檢查
                use App\Models\Product;
                public function store(Request $request)
                {   
                    $messages = [
                        'required' => ':attribute 是必要的', // 必填欄位 => '(自動抓欄位名稱)  是必要的'
                        'between'=>':attribute 的輸入 :input 不在 :min 和 :max 之間' // 之間 => '(自動抓欄位名稱) 的輸入 (input) 不在 (min) 和 (max) 之間'
                    ];
                    $validator = Validator::make($request->all(),[
                        'cart_id'=>'required|integer', //設定此欄位為必填且為int的意思，找其他意思可以到參考連結找：https://laravel.com/docs/10.x/validation#available-validation-rules
                        'product_id'=>'required|integer',
                        'quantity'=>'required|integer|between:1,10',//設定此欄位為必填且為int，且數值必須為1~10的意思
                    ],$messages);
                    if($validator->fails()){ // 如果$validator驗證fails了，就回傳errors
                        return response($validator->errors(),400); // 回傳$validator的errors訊息，並回傳400告訴使用者，此為錯誤訊息
                    }
                    $validateData = $validator->validate(); // 此為將驗證過的資料，儲存到$validateData。
            
                    // 此步驟新增的資料 ↓
                    $product = Product::find($validateData['product_id']);
                    if(!$product->checkQuantity($validateData['quantity'])){ // 如果檢查數量有問題就進來判斷
                        return response($product->title.'數量不足',400);
                    }
                    // 此步驟新增的資料 ↑

                    $cart = Cart::find($validateData['cart_id']); //將結果放入$cart中
                    $result = $cart->cartItems()->create(['product_id' => $product->id,
                                                          'quantity' =>$validateData['quantity'],]); 
                    return response()->json($result);
                }
            // 2.到(Product.php)  
                public function checkQuantity($quantity){
                    if($this->quantity < $quantity){
                        return false; // 資料庫內的數量小於要被訂購的數量，就回傳false
                    }   
                    return true; // 沒事就true
                }
            // 3.Postman測試POST
                // 位置：http://127.0.0.1:8000/cart-items
        // B.在結帳時，要告訴使用者商品不足
            // 1.到(Cart.php)改寫結帳函式
                public function checkout(){
                    //檢查要在創造前
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $product = $cartItem->product;
                        if(!$product->checkQuantity($cartItem->quantity)){
                            return $product->title.'數量不足'; //執行到此會直接結束foreach，並回傳此
                        }
                    }
                    $order = $this->order()->create([
                        'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
                    ]);
                    if($this->user->level ==2){
                        $this->rate = 0.8; //如果是vip(使用者等級2)，就打八折
                    }
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $order->orderItems()->create([
                            'product_id'=>$cartItem->product_id,
                            'price' => $cartItem->product->price *$this->rate
                        ]);
                    }
            
                    $this->update(['checkouted'=>true]);
                    $order->orderItems;
                    return $order; // 回傳訂單長甚麼樣子
                }
            // 2.到(Product.php)，產品購買後要更新產品數量
                protected $guarded =['']; // 補上此，讓資料表product的欄位都是可以被更新的
            // 3.到(Cart.php)
                public function checkout(){
                    //檢查要在創造前
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $product = $cartItem->product;
                        if(!$product->checkQuantity($cartItem->quantity)){
                            return $product->title.'數量不足'; //執行到此會直接結束foreach，並回傳此
                        }
                    }
                    $order = $this->order()->create([
                        'user_id'=> $this->user_id, // 直接指向正被使用的購物的user_id
                    ]);
                    if($this->user->level ==2){
                        $this->rate = 0.8; //如果是vip(使用者等級2)，就打八折
                    }
                    foreach($this->cartItems as $cartItem){ // 把購物車的cartitem每個都轉成orderitem
                        $order->orderItems()->create([
                            'product_id'=>$cartItem->product_id,
                            'price' => $cartItem->product->price *$this->rate
                        ]);
                        // 此步驟新增的資料 ↓
                        $cartItem->product->update(['quantity'=>$cartItem->product->quantity - $cartItem->quantity]);
                        // 購買後將產品減少
                        // 此步驟新增的資料 ↑
                    }
            
                    $this->update(['checkouted'=>true]);
                    $order->orderItems;
                    return $order; // 回傳訂單長甚麼樣子
                }
            // 4.Postman測試POST
                // 位置：http://127.0.0.1:8000/carts/checkout

                
                
                
            
            

            


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

    // 10-4.資料驗證(資料清洗)
        // 概念：例如會員機制的密碼數要9碼，就來檢驗是否有超過9碼，前後端都會有檢查系統，因此除了前端檢查以外，後端
                // 的二次檢查會比較保險，也有可能前端與後端的密碼數量通知不同，才要分開設置，又分為兩種設置方法。
        // A.在Controller進行驗證
            // I.到(CartItemController.php)
                use Illuminate\Support\Facades\Validator;
                public function store(Request $request)
                {   
                    $messages = [
                        'required' => ':attribute 是必要的', // 必填欄位 => '(自動抓欄位名稱)  是必要的'
                        'between'=>':attribute 的輸入 :input 不在 :min 和 :max 之間' // 之間 => '(自動抓欄位名稱) 的輸入 (input) 不在 (min) 和 (max) 之間'
                    ];
                    $validator = Validator::make($request->all(),[
                        'cart_id'=>'required|integer', //設定此欄位為必填且為int的意思，找其他意思可以到參考連結找：https://laravel.com/docs/10.x/validation#available-validation-rules
                        'product_id'=>'required|integer',
                        'quantity'=>'required|integer|between:1,10',//設定此欄位為必填且為int，且數值必須為1~10的意思
                    ],$messages);
                    if($validator->fails()){ // 如果$validator驗證fails了，就回傳errors
                        return response($validator->errors(),400); // 回傳$validator的errors訊息，並回傳400告訴使用者，此為錯誤訊息
                    }
                    $validateData = $validator->validate(); // 此為將驗證過的資料，儲存到$validateData。
            
                    // 新增資料
                    DB::table('cart_items')->insert(['cart_id' => $validateData['cart_id'],
                                                'product_id' => $validateData['product_id'],
                                                'quantity' =>$validateData['quantity'],
                                                'created_at' => now(),
                                                'updated_at'=>now()]);
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // II. Postman測試POST
                // 位置：http://127.0.0.1:8000/cart-items


        // B.定義 Validator
            // 參考網站：https://laravel.com/docs/10.x/validation#form-request-validation
            // I.終端輸入"php artisan make:request (Validator名稱)" -> php artisan make:request UpdateCartItem
            // II.到app/Http/Requests/UpdateCartItem.php
                // 授權驗證部分，還未用到因此先暫時改成true
                public function authorize(): bool
                {
                    return true;
                }
                public function rules(): array
                {
                    return [
                        'quantity'=>'required|integer|between:1,10'
                    ];
                }
                public function messages(): array //這裡不須額外寫甚麼時候執行，因為Laravel會自己來找
                {
                    return [
                        'quantity.between'=>'數量必須小於10' //between驗證到錯誤時，回傳的messages
                    ];
                }
            // III.新增檔案/app/Http/Requests/APIRequest.php
                // 因沒有新增APIRequest.php檔案，驗證過後的回覆，會被傳到上一頁，導致回覆被洗掉
                // 檔案內的程式碼
                    <?php

                    namespace App\Http\Requests;
                    
                    use Illuminate\Foundation\Http\FormRequest;
                    
                    class APIRequest extends FormRequest
                    {
                        
                    }
            // IV.回到app/Http/Requests/UpdateCartItem.php
                // 將
                class UpdateCartItem extends FormRequest {}
                //  ↓ 改成 ↓ 
                class UpdateCartItem extends APIRequest {}
                // 不需要增加use的關鍵字來引入，因為他們在同一個檔案底下
                // 資料層級變成 爺爺:FormRequest 父親:APIRequest 兒子:UpdateCartItem
            // V.回到/app/Http/Requests/APIRequest.php
                use Illuminate\Http\Exceptions\HttpResponseException;
                class APIRequest extends FormRequest
                {
                    protected function failedValidation(Validator $validator)
                    // 覆蓋掉FormRequest中的函式，並使用Illuminate\Contracts\Validation\Validator;來幫助檢查
                    {
                        throw new HttpResponseException(response(['errors'=>$validator->errors(),400])); //回傳錯誤
                    } 
                }
            // VI.到/app/Http/Controllers/Web/CartItemController.php
                use App\Http\Requests\UpdateCartItem;
                public function update(UpdateCartItem $request, string $id)
                {
                    //更新資料
                    $form = $request->validated(); //驗證好的資料
                    DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
                                            ->update(['quantity' =>$form['quantity'],
                                                    'updated_at'=>now()]);
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // VII.Postman測試PUT
                // 位置：http://127.0.0.1:8000/cart-items/2
    // 10-5.Eloquent ORM(Model)
        // ORM：指資料庫與應用程式的中間層，可有效保護資料庫，例：DB,Eloquent 都可當作Laravel的ORM。
        // A.為何使用Eloquent？
                // i.Eloquent相當於Laravel MVC中的M。
                // ii.支援DB常見的各種SQL語法
                // iii.提供現在化常見預設結構功能，包括PK(Primary Key)、Timestamp等等
                // iv.方便建立與其他資料表連結，讓程式碼更簡潔易懂(如多欄位一次運算)
        // B.建構Model(Eloquent ORM(Model))
            // I.終端輸入"php artisan make:model 模組名稱(此處為CartItem)"
            // II.到/app/Model/CartItem.php
            // III.終端輸入"composer dump-autoload" ，使得檔案快速重新讀取一次
            // IV.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
            // V.終端輸入"CartItem::all()" 模組名稱(此處為CartItem)::all()，撈出模組內的所有資料，包含DB內資料表cart_items的資料
                // 可以抓到DB內資料表cart_items的資料的原因：會將Class的CartItem轉成小寫的，並且也將cart_items的資料抓過來
            // VI.終端輸入"CartItem::where('id','>',3)->get()" ，跟一般的語法一樣可以使用

        // C.設定欄位可否更新(白名單)
            // I.到/app/Model/CartItem.php(自定義的)
                class CartItem extends Model
                {
                    use HasFactory;
                    // 為甚麼protected，單純是官方設定的，記得就對了
                    protected $fillable = ['quantity','product_id'];// 此處為白名單功能
                }
            // II.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
            // III.終端輸入"CartItem::find(3)"，找出id=3的資料。
            // IV.終端輸入"CartItem::find(3)->update(['product_id=>2,'quantity'=>10])"，測試修改資料
                // 此處若protected $fillable的部分沒有'product_id'就會回傳errors。
            // V.終端輸入"CartItem::find(3)"，查看id=3的資料是否正常被修改，可注意updated_at欄位，因為使用update方法會自動更新時間

        // D.設定欄位可否更新(黑名單)
            // I.到/app/Model/CartItem.php(自定義的)
                class CartItem extends Model
                {
                    use HasFactory;
                    // 為甚麼protected，單純是官方設定的，記得就對了
                    protected $guarded = ['cart_id']; // 此處為黑名單
                }
            // II.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
            // III.終端輸入"CartItem::find(3)"，找出id=3的資料。
            // IV.終端輸入"CartItem::find(3)->update(['cart_id'=>10])"，測試修改資料
                // 回傳依然是true，但資料不會被改變
            // V.終端輸入"CartItem::find(3)"，查看id=3的資料是否正常被修改，可注意updated_at欄位，因為使用update方法會自動更新時間
        
        // E.設定欄位資料不可被撈取
            // I.到/app/Model/CartItem.php(自定義的)
                class CartItem extends Model
                {
                    use HasFactory;
                    // 為甚麼protected，單純是官方設定的，記得就對了
                    protected $hidden = ['updated_at']; // 此處為不會被回傳(response)的資料
                }
            // II.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
            // III.終端輸入"CartItem::find(3)"，找出id=3的資料，會發現updated_at的欄位沒有被回傳或者被加上了"#"，變成#updated_at
        
        // F.設定欄位資料被撈取時執行函式改變資料
            // I.到/app/Model/CartItem.php(自定義的)
                class CartItem extends Model
                {
                    use HasFactory;
                    // 為甚麼protected，單純是官方設定的，記得就對了
                    protected $appends = ['current_price']; // 自訂屬性
                    public function getCurrentPriceAttribute(){ //命名方式為固定的"get屬性Attribute"，如若沒有正確輸入，會跑出null
                        return $this->quantity * 10; // 被撈到的資料的欄位quantity*10
                    }
                }
            // II.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
            // III.終端輸入"CartItem::find(3)->current_price "，找出id=3的資料，並回傳quantity欄位的數量*10，如若沒有正確輸入，會跑出null
    // 10-6.ORM 中運用 Relation
        // I.終端輸入"php artisan make:model Product"
        // II.終端輸入"php artisan make:model Cart"
        // III.讓CartItem.php屬於Prodcut.php和Cart.php，屬於一對多的關係，一是Product，多是讓CartItem
                // (CartItem.php)
                public function product(){// 使用單數是因為他是一對多，所以product是單數
                    // 此函數執行時，會執行將此檔案屬於Product，去尋找資料表Product有無對應的product_id
                    return $this->belongsTo(Product::class);
                }
            
                public function cart(){// 使用單數是因為他是一對多，所以cart是單數
                    // 此函數執行時，會執行將此檔案屬於cart，去尋找資料表cart有無對應的cart_id
                    return $this->belongsTo(Cart::class);
                }
        // IV.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
        // V.終端輸入"CartItem::find(3)"，找出id=3的資料
        // VI.終端輸入"CartItem::find(3)->product"，可以找出找出id=3資料中的產品的屬性
        // VII.終端輸入"CartItem::find(3)->cart"，可以找出找出id=3資料中的購物車的屬性
        // VIII.建立雙向功能，接著來到Cart.php與Product.php
            class Cart extends Model
            {
                use HasFactory;
                public function cartItems(){
                    return $this->hasMany(CartItem::class); //設定好Cart要怎麼去取得CartItem
                }
            }
        // IX.終端輸入"composer dump-autoload" ，使得檔案快速重新讀取一次
        // X.終端輸入"php artisan tinker"，建立一個執行Laravel的環境，使得可以快速驗證程式是否正確。
        // XI.終端輸入"Cart::find(2)"，撈資料
        // XII.終端輸入"Cart::find(2)->cartItems"，打撈所有跟Cart_id=2有相關的cartItems，且全是Collection，因為使用的是hasMany
        // XII.終端輸入"Product::find(2)->productItems"，打撈所有跟Product_id=2有相關的ProductItems，且全是Collection，因為使用的是hasMany
        
    // 10-7.Query Builder 優化為 ORM
        // A.修改(CartItemController.php)store
            // I.到(CartItemController.php)改使用Model，修改store
                use app\Models\Cart;
                use app\Models\CartItem;
                public function store(Request $request)
                {   
                    $messages = [
                        'required' => ':attribute 是必要的', // 必填欄位 => '(自動抓欄位名稱)  是必要的'
                        'between'=>':attribute 的輸入 :input 不在 :min 和 :max 之間' // 之間 => '(自動抓欄位名稱) 的輸入 (input) 不在 (min) 和 (max) 之間'
                    ];
                    $validator = Validator::make($request->all(),[
                        'cart_id'=>'required|integer', //設定此欄位為必填且為int的意思，找其他意思可以到參考連結找：https://laravel.com/docs/10.x/validation#available-validation-rules
                        'product_id'=>'required|integer',
                        'quantity'=>'required|integer|between:1,10',//設定此欄位為必填且為int，且數值必須為1~10的意思
                    ],$messages);
                    if($validator->fails()){ // 如果$validator驗證fails了，就回傳errors
                        return response($validator->errors(),400); // 回傳$validator的errors訊息，並回傳400告訴使用者，此為錯誤訊息
                    }
                    $validateData = $validator->validate(); // 此為將驗證過的資料，儲存到$validateData。
                    $cart = Cart::find($validateData['cart_id']); //將結果放入$cart中
                    $result = $cart->cartItems()->create(['product_id' => $validateData['product_id'],
                                                        'quantity' =>$validateData['quantity'],]); 
                    // 關於$result
                        // 呼叫cart的附屬(Model)cartitem回傳資料，並去cart_items底下建立，
                        // cart_id早已在Cart::find($validateData['cart_id']);時被指定，因此create內不需要再填入cart_id
                        // created_at，Model會處理，因此此處可以不寫
                        // updated_at，Model會處理，因此此處可以不寫
                    return response()->json($result);
                }

            // II.Postman測試POST
                // 位置：http://127.0.0.1:8000/cart-items
        
        // B.修改(CartItemController.php)update
            // I.修改update
                public function update(UpdateCartItem $request, string $id)
                {
                    //更新資料
                    $form = $request->validated(); //驗證好的資料
                    $item = CartItem::find($id);
                    // $item->update(['quantity'=>$form['quantity']]); // 不想使用fill的話也可以使用update，那$item->save(); 就可以刪除。
                    $item->fill(['quantity'=>$form['quantity']]); 
                    // fill函式是填好但不儲存，因資料可能要經過很多個地方，每個地方填的東西都不同，，因此最後在儲存，能夠有效的減少下SQL的次數，來增進效能。
                    $item->save(); // 將fill的資料經檢驗完後，儲存(更新)進去。
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // II.Postman測試PUT
                // 位置：http://127.0.0.1:8000/cart-items/2
        // C.修改(CartItemController.php)Destory
            // I.修改Destory
                public function destroy(string $id)
                {
                    //刪除資料
                    CartItem::find($id)->delete();
                    return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
                }
            // II.Postman測試DELETE
                // 位置：http://127.0.0.1:8000/cart_items/3
        
        // D.修改(CartController.php)Index，注意此處並非(CartItemController.php)，與前面的檔案不相同
            // I.修改Index
            public function index()
            {
                $cart = Cart::with(['cartItems'])->firstOrCreate(); // 此處的cartItems為/app/Http/Model/Cart.php的cartItems
                // with()，Model會自動去尋找Cart相關聯的資料(有建立過關聯的)，並順便撈出來，可解決n+1 cubed的問題
                //       ，且with會暫存，因此不必下重複的SQL語法來撈取同樣的資料
                // firstOrCreate()，Model判斷表中有無資料，若無則自動建立
                return response($cart); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
            }

// 11.資料優化-預設資料產生器 - Seeder
    // 位置：database/seeder/DatabaseSeeder.php
        // A.情境：產品資料要先生成 (Create，可重複生成)
            // I.到(DatabaseSeeder.php)
                use App\Models\Product;
                public function run(): void
                {
                    //使用Product::create()，在run code時可以自動建立Product
                    Product::create(['tiitle'=>'測試資料','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                    Product::create(['tiitle'=>'測試資料2','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                    Product::create(['tiitle'=>'測試資料3','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                        //rand(0,300)，產生0到300的整數
                }
            // II.終端輸入"php artisan db:seed"，創造run()的資料
            // III.到資料表product確定資料生產成功，即可

        // B.情境：自創Seeder (upsert，不會重複生成，保持單一性，依照設定的key值，製造出單一性的資料)
            // I.終端輸入"php artisan make:seeder ProductSeeder"
            // II.到(database/seeder/ProductSeeder.php)中
                public function run(): void
                {
                    Product::upsert([
                        ['id'=>6,'title'=>'固定資料','content'=> '固定內容','price'=> rand(0,300),'quantity'=>20],
                        ['id'=>7,'title'=>'固定資料','content'=> '固定內容','price'=> rand(0,300),'quantity'=>20],
                ],['id'],['price','quantity']); 
                    // upsert(陣列1,陣列2,陣列3)：陣列1是產生固定的資料，陣列2是陣列1的key值為何，使得upsert進行生產時依據key值判斷是否需要建立，陣列3為可變更的白名單
                    //upsert();，此指令版本需大於8.9，可使用"php artisan -V"查看Laravel版本，
                    // 如過舊可使用"composer update laravel/framework"，來更新Laravel，
                    // 如果報錯記憶體不足，可使用"COMPOSER_MEMORY_LIMIT=-1 composer update laravel/framework"
                }
            // III.終端輸入"php artisan db::seed --class=ProductSeeder"，指定跑ProductSeeder.php
            // IV.到(DatabaseSeeder.php)，將程式碼變成以下
                public function run(): void
                {
                    //使用Product::create()，在run code時可以自動建立Product
                    Product::create(['title'=>'測試資料','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                    Product::create(['title'=>'測試資料2','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                    Product::create(['title'=>'測試資料3','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
                        //rand(0,300)，產生0到300的整數
                    $this->call(ProductSeeder::class); // 前面的執行完，幫我執行ProductSeeder.php的東西
                    $this->command->info('產生固定 product 資料'); // 產生文字在終端，提醒目前在產生資料
                }
            // V.終端"php artisan db:seed"，會跳出"產生固定 product 資料"
            // VI.到資料表product，看看資料有沒有正常產出

// 12.資料優化-軟刪除Soft delete 介紹
    // 概念：軟刪除並非實質將資料刪除，而是標記一個欄位(Deleted at:)"幾月幾號幾點幾分Delete"，因此軟刪除的意思是此筆資料在意義上是被刪除的，好處是資料易復原。
        // I.終端輸入"php artisan make:migration add_soft_delete_to_cart_items"
        // II.到新增的Migreation(add_soft_delete_to_cart_items)
            public function up(): void
            {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->softDeletes(); // 預設標記Deleted at:
                });
            }
            public function down(): void
            {
                Schema::table('cart_items', function (Blueprint $table) {
                    $table->dropSoftDeletes(); 
                });
            }
        // III.終端輸入"php artisan migrate"，此時資料表cart_items，會出現deleted at欄位
        // IV.到(Cart.php)Model的
            use Illuminate\Database\Eloquent\SoftDeletes;
            use SoftDeletes;
        // V.到(CartItemController.php)，程式碼無變動
            public function destroy(string $id)
            {
                CartItem::find($id)->delete(); //軟刪除
                // CartItem::withTrashed()->find($id)->forceDelete(); //硬刪除，直接刪除
                return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
            }
        // VI.Postman測試DELETE
            // 位置：http://127.0.0.1:8000/cart-items/6
        // VII.會看到資料庫的某項資料已變成deleted at，在撈資料時，在"php artisan tinker"
            // 使用Model方式，"CartItem::where('create_at','>','2023-05-31')->get();被軟刪除的資料將不會回傳
            // 使用DB方式，"DB::table('cart_items')where('create_at','>','2023-05-31')->get();"，被軟刪除的資料將會回傳
            
                    






            
















