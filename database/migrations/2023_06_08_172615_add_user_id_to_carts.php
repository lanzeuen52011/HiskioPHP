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
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->after('id'); // 此處使用foreignId而非integer，是因為要綁定使用者，以防止不存在的人，在使用不存的Cart。
            // constrained，表示user_id綁定在users內的id欄位
            // after，使欄位產生在id之後
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            // dropConstrainedForeignId會先將constrained的綁定關係解除，才進行drop(欄位取消)。
        });
    }
};
