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
        Schema::create('order_components', function (Blueprint $table) {

            /* ご注意： 注文当時のデータを残すため、外部キーにすべきところ／すべきでないところは気をつけてください */

            $table->id();
            $table->string('order_unique_id')->comment('注文ID');
            $table->unsignedBigInteger('order_id')->comment('注文ID');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('component_id')->comment('パーツID');
            $table->string('component_name')->comment('パーツ名');
            $table->string('group_name')->comment('パーツ名');
            $table->string('key')->comment('キー');
            $table->integer('amount')->comment('金額');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('component_id')->references('id')->on('product_components');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_components');
    }
};
