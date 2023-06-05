<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
