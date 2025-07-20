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
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->comment('注文ID');
            $table->unsignedBigInteger('multi_auth_user_id')->comment('ユーザーID');
            $table->string('status_from')->comment('元のステータス'); // App\Enums\OrderStatus
            $table->string('status_to')->comment('新しいステータス'); // App\Enums\OrderStatus
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('multi_auth_user_id')->references('id')->on('multi_auth_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
