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
        // order_status_histories
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('multi_auth_user_id')->nullable()->comment('ユーザーID')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('multi_auth_user_id')->nullable(false)->comment('ユーザーID')->change();
        });
    }
};
