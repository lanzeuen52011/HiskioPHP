<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateCartItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

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

        $product = Product::find($validateData['product_id']);
        if(!$product->checkQuantity($validateData['quantity'])){ // 如果檢查數量有問題就進來判斷
            return response($product->title.'數量不足',400);
        }

        $cart = Cart::find($validateData['cart_id']); //將結果放入$cart中
        $result = $cart->cartItems()->create(['product_id' => $product->id,
                                              'quantity' =>$validateData['quantity'],]); 
        // 關於$result
            // 呼叫cart的附屬(Model)cartitem回傳資料，並去cart_items底下建立，
            // cart_id早已在Cart::find($validateData['cart_id']);時被指定，因此create內不需要再填入cart_id
            // created_at，Model會處理，因此此處可以不寫
            // updated_at，Model會處理，因此此處可以不寫

        // 新增資料(使用DB時開啟，使用Model時關閉)
        // DB::table('cart_items')->insert([ 'cart_id' => $validateData['cart_id'], // 因此此行可以註解掉
        //                             'product_id' => $validateData['product_id'],
        //                             'quantity' =>$validateData['quantity'],
        //                             'created_at' => now(), // Model內已經有寫好會處理，因此此處可以刪掉
        //                             'updated_at'=>now() // Model內已經有寫好會處理，因此此處可以刪掉
        //                         ]); 
        // return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
        return response()->json($result);
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
    public function update(UpdateCartItem $request, string $id)
    {
        //更新資料
        $form = $request->validated(); //驗證好的資料
        $item = CartItem::find($id);
        // $item->update(['quantity'=>$form['quantity']]); // 不想使用fill的話也可以使用update，那$item->save(); 就可以刪除。
        $item->fill(['quantity'=>$form['quantity']]); // 將找到的cart_item底下的id的數量，變成表單的數量。
        // fill函式是填好但不儲存，因資料可能要經過很多個地方，每個地方填的東西都不同，，因此最後在儲存，能夠有效的減少下SQL的次數，來增進效能。
        $item->save(); // 將fill的資料經檢驗完後，儲存(更新)進去。
        // DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
        //                         ->update(['quantity' =>$form['quantity'],
        //                                   'updated_at'=>now()]);
        return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //刪除資料
        CartItem::find($id)->delete(); //軟刪除
        // CartItem::withTrashed()->find($id)->forceDelete(); //硬刪除

        // DB::table('cart_items')->where('id',$id) // 新增where是因為如果只有使用update會更新全部資料，此處只需要更新對應的itemid即可
        //                         ->delete();
        return response()->json(true); // 回傳true是告訴前端(或者測試人員)，正確回傳(json為使用json格式回傳)
    }
}
