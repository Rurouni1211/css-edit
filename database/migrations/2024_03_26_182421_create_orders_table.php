<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            /* ご注意： 注文当時のデータを残すため、外部キーにすべきところ／すべきでないところは気をつけてください */

            $table->id();
            $table->string('order_unique_id')->unique()->comment('注文ID');
            $table->unsignedBigInteger('customer_id')->comment('顧客ID');
            $table->unsignedBigInteger('shop_id')->nullable()->comment('ショップID');
            $table->string('status')->comment('ステータス'); // App\Enums\OrderStatus に定義
            $table->string('customer_name')->comment('注文者名');
            $table->string('customer_email')->comment('注文者メールアドレス');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('product_name')->comment('商品名');
            $table->integer('total_amount')->nullable()->comment('合計金額');
            $table->integer('consumption_tax_rate')->nullable()->comment('消費税率');
            $table->integer('consumption_tax')->nullable()->comment('消費税額');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
