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
        Schema::create('multi_auth_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->comment('ユーザータイプ'); // App\Enums\UserType
            $table->unsignedBigInteger('customer_id')->nullable()->comment('顧客ID');
            $table->unsignedBigInteger('admin_id')->nullable()->comment('管理者ID');
            $table->unsignedBigInteger('shop_id')->nullable()->comment('店舗ID');
            $table->unsignedBigInteger('artisan_id')->nullable()->comment('職人ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multi_auth_users');
    }
};
