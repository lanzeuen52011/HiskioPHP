<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::upsert([
            ['id'=>9,'title'=>'固定資料','content'=> '固定內容','price'=> rand(0,300),'quantity'=>20],
            ['id'=>10,'title'=>'固定資料','content'=> '固定內容','price'=> rand(0,300),'quantity'=>20],
    ],['id'],['price','quantity']); 
        // upsert(陣列1,陣列2,陣列3)：陣列1是產生固定的資料，陣列2是陣列1的key值為何，使得upsert進行生產時依據key值判斷是否需要建立，陣列3為可變更的資料
        //upsert();，此指令版本需大於8.9，可使用"php artisan -V"查看Laravel版本，
        // 如過舊可使用"composer update laravel/framework"，來更新Laravel，
        // 如果報錯記憶體不足，可使用"COMPOSER_MEMORY_LIMIT=-1 composer update laravel/framework"
        
    }
}
