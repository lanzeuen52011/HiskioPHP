<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create(); // factory是單元測試時會教

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        //使用Product::create()，在run code時可以自動建立Product
        Product::create(['title'=>'測試資料','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
        Product::create(['title'=>'測試資料2','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
        Product::create(['title'=>'測試資料3','content'=> '測試內容','price'=> rand(0,300),'quantity'=>20]); 
            //rand(0,300)，產生0到300的整數
        $this->call(ProductSeeder::class); // 前面的執行完，幫我執行ProductSeeder.php的東西
        $this->command->info('產生固定 product 資料'); // 產生文字在終端，提醒目前在產生資料
    }
}
