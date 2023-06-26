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
        Schema::create('log_errors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0); //紀錄發生錯誤時是哪位user在操作的
            $table->text('exception')->nullable(); //紀錄錯誤的類別
            $table->text('message')->nullable(); //紀錄錯誤彈出時的訊息
            $table->integer('line')->nullable(); //紀錄錯誤發生在第幾行
            $table->json('trace')->nullable(); //紀錄追蹤錯誤觸發的執行流程
            $table->string('method')->nullable(); //紀錄user是使用GET還是其他方法時出的錯誤
            $table->json('params')->nullable(); //紀錄user回傳給後端的參數
            $table->text('uri')->nullable(); //紀錄user使用時，所打到的網址
            $table->text('user_agent')->nullable(); //紀錄user使用的瀏覽器，或者根本就是機器人
            $table->json('header')->nullable(); //紀錄user的屬性
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_errors');
    }
};
