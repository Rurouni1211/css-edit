<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make all customer_* fields in orders table nullable
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make customer fields nullable
            $table->unsignedBigInteger('customer_id')->nullable()->comment('顧客ID')->change();
            $table->string('customer_name')->nullable()->comment('注文者名')->change();
            $table->string('customer_email')->nullable()->comment('注文者メールアドレス')->change();
        });
    }

    /**
     * Reverse the migrations.
     * Restore customer_* fields to their original non-nullable state
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make customer fields required again
            $table->unsignedBigInteger('customer_id')->comment('顧客ID')->change();
            $table->string('customer_name')->comment('注文者名')->change();
            $table->string('customer_email')->comment('注文者メールアドレス')->change();
        });
    }
};
