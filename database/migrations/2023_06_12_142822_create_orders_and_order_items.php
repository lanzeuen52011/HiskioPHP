<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 因function up(){}是先建立資料表orders，因此此處要先刪除order_items
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
