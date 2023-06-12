<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Model寫法
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
            
            

        // DB寫法
        // $cart = DB::table('carts')->get()->first(); //取得資料表carts的第一個
        // // empty是Laravel中的Helper，用來判斷某變數是否為空
        // if(empty($cart)){
        //     // 此處使用insert與SQL語法一樣，now()則是當前系統時間
        //     DB::table('carts')->insert(['created_at' => now(),'updated_at'=>now()]);
            
        //     // 資料更新後再重新取得資料
        //     $cart = DB::table('carts')->get()->first(); 
        // }

        // $cart_Items = DB::table('cart_items')->where('cart_id',$cart->id)->get(); // 到cart_items資料表下撈取cart_id=1的資料
        // $cart = collect($cart);
        // $cart['item'] =  collect($cart_Items);

        return response($cart); // 茲因得到的可能是個物件或者其他類型，因此需轉成Collection後才可回傳
    }


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
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
