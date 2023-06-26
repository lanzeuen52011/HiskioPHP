<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {  
        $productCount = Product::count(); // 執行SQL裡面的count函數，如果要sum就改成sum('欄位名稱')
        $dataPerpage = 2; // 設定成一頁兩筆資料
        $productPages = ceil($productCount / $dataPerpage); 
        // 會有幾頁的資料，會使用ceil函式(無條件進位)是因為假如最後一頁剩一筆資料或者沒填滿，還是必須顯示出來，會多一頁。
        
        $currentPage = isset($request->all()['page']) ? $request->all()['page'] : 1 ; // 當前頁數為幾，如果沒有回傳頁數則為1
        // 此處的productBy，可參考：SQL 特殊參數 - product
        // 因此處在get以前，都只是單純的SQL函式，處理完之後沒有下get，它會不知道要把資料送過來
        $products = Product::orderBy('created_at','asc') // 使用with，在撈資料前，先撈出關聯的Model資料，可使前端執行時SQL語法減少
                            // 此處的productItems.product可以將productItem與product的關聯都拉進來
                        ->offset($dataPerpage * ($currentPage - 1)) // 假如第1頁，就從第1筆資料(引數為[0])開始搜尋，第2頁就是2*(2-1)，從第三筆資料(引數為[2])開始搜尋
                        ->limit($dataPerpage) // 限制每頁兩個資料
                        ->get();

        return view('admin.products.index',['products'=>$products,
                                          'productCount'=>$productCount,
                                          'productPages'=>$productPages,
                                        ]);
    }
    public function uploadImage(Request $request)
    {
        $file = $request->file('product_image'); // 來自前端的(admin_modal.blade.php)的(<input type="file" id="product_image" name="product_image">)
        $productId = $request->input('product_id');
        
        if(is_null($productId)){ // 如果$productId是空的就不給他傳
            return redirect()->back()->withErrors(['msg'=>'參數錯誤']);
        }
        if(is_null($file)){ // 如果檔案是空的就不給他傳
            return redirect()->back()->withErrors(['msg'=>'參數錯誤']);
        }
        // 上傳檔案
        $product = Product::find($productId);
        $path = $file->store('images'); // 存到public/images會變成存到storage/app/public/images
        $product->image()->create([
            'filename'=> $file->getClientOriginalName(),
            'path'=>$path
        ]);
        return redirect()->back();
    }
}
