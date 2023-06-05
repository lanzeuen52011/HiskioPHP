<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // 新增資料
        $form = $request->all();
        DB::table('cart_items')->insert(['cart_id' => $form['cart_id'],
                                    'product_id' => $form['product_id'],
                                    'quantity' =>$form['quantity'],
                                    'created_at' => now(),
                                    'updated_at'=>now()]);
        return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
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
        //更新資料
        $form = $request->all();
        DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
                                ->update(['quantity' =>$form['quantity'],
                                          'updated_at'=>now()]);
        return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //刪除資料
        DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
                                ->delete();
        return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
    }
}
