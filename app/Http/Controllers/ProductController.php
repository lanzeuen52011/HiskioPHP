<?php

namespace App\Http\Controllers; //重要，如果在其他模塊需要用到此檔案，這個可以幫助其使用此檔案

use App\Http\Controllers\Controller; // 使用地址中的檔案
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    // // 3-1.網站資料傳輸基礎 Request & Response
        // //當達到URI的時候，可以指定URI來指定執行此函式
        // dump($request);
        // //dump();類似var_dump();，此處dump執行方式為先使用"php artisan route:list"，
        // // 找到從哪個路由會執行此函式index()，再使用"php artisan serve"的網址列加上路由，
        // // 以此處為例：http://127.0.0.1:8000/product
        // // 把網址變成http://127.0.0.1:8000/product?name=John，會在$request['query']的參數找到包含John的陣列
        // // $request['server']，會有PHP版本路徑IP等的資訊內容
        // // $request['cookies']，可以拿來放暫存的資料，如廣告等等
        // // $request['headers']，來表示request的屬性

        // dump($request->all()); //獲取的是整個 HTTP 請求中的所有數據，包括表單提交的數據、URL 中的查詢參數、文件上傳等並返回一個關聯數組，'name'=>John，但資料會顯示在網址列上
        // dump($request->query()); //URL中的查詢參數 ? 開頭的部分傳遞的參數並返回一個關聯數組，'name'=>John，資料不會顯示在網址列上
        // dump($request->input('name')); //會得到$request['query']的參數，但只取得'name'的值，也就是'John'
        // dump($request->input('age')); //會得到$request['query']的參數，但只取得'age'的值，但此處沒有age為key的資料，回傳NULL
        // dump($request->input('age',10)); //'age'的值使用者沒有回傳的話，會帶入預設值10
        // dump($request->path()); //會得到根目錄後的網址

        // // return response()->view('welcome'); //此處的welcome就是在web.php中的return view('welcome');
        // // return response('123',200); //response(內容,連線狀態);其中連線狀態常回傳 200(連線成功)、400(資料有誤)、500(伺服器壞掉了)
        //     // 要更多的狀態碼，可以查詢Http status code
        // return redirect('/'); //導頁，就是自動換頁，此處就是輸入http://127.0.0.1:8000/product 會直接被打回 http://127.0.0.1:8000/
        
    // 3-2.Read讀取資料    
        $data=$this->getData(); // 從此處來得到getData
        dump($data);
        return response($data); // 並回傳到網頁上
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() //使用GET
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //使用POST
    {
        //Request，屬於Laravel內建的物件，用來檢查參數的格式與資料類別
        $data= $this->getData(); //使用getData函數並裝到$data內
        $newData = $request->all(); //把request的資料抓出來並裝到$newData內

        // 非collection形式
        // array_push($data,$newData); //把$newData push到$data內

        // collection形式
        $data->push(collect($newData));
        dump($data);

        return response($data); //回傳$data

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data = $this->getData();
        $data = $data->filter(function($product)use($id){ //此處必須使用use將$id帶入函式中
            return $product['id'] != $id;
        });
        // return response($data); //回傳剩下的資料
        return response($data->values()); //回傳剩下的資料的值
    }
    
    public function getData(){
        // 以此為回傳資料
        // Collection型式，注意其效果在index需使用dump來呈現，若使用return會看不出差別，並去Postman使用GET方法底下的Preview來觀測。
        // 非collection形式
            // array_push($data,$newData); //把$newData push到$data內

        // collection形式
            // $data->push(collect($newData));
            // dump($data);
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

        // 非Collection型式
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
}
