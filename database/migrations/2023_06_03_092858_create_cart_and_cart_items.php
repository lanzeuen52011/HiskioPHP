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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id');//因資料表cart與資料表cart_items是有相關(一個購物車有很多個購物項目，因此一對多)
                                         // 的因此需要foreignId
            $table->foreignId('product_id');//因prodct不可能只有一個人買，因此此處可以轉換成很多人的購物車內擁有同一個項目的產品(一對多)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('cart_items');
    }
};
