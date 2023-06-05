<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // up()通常的更動都會在up內，更動
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // 自動設定ID
            $table->string('title'); // 設定欄位
            $table->string('content'); // 設定欄位
            $table->integer('price'); // 設定欄位
            $table->integer('quantity'); // 設定欄位
            $table->timestamps(); // 以時間戳的方式產生兩個欄位：Create Date、Update Date
        });
    }

    /**
     * Reverse the migrations.
     */
    // down()回傳資料庫狀態，復原
    public function down(): void
    {
        Schema::dropIfExists('products'); //如果'products'表格存在就刪除掉
    }
};
