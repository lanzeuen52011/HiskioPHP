<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Jobs\UpdateProductPrice;
use Illuminate\Support\Facades\Redis;

class ToolController extends Controller
{
    public function updateproductprice()
    {
        $products = Product::all();
        foreach($products as $product){
            UpdateProductPrice::dispatch($product)->onQueue('tool'); //dispatch的意思是，建立一個Job進去資料表Jobs，當運行時變會知道是甚麼工作要被執行
        }
    }
    public function createProductRedis()
    {
        Redis::set('products',json_encode(Product::all())); // 將Product的所有資料放入Redis的products中儲存
        // 因直接將資料塞進去的話，會是以php class的形式塞入，因此需使用"json_encode()"
    }
}
