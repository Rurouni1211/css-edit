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
        Schema::create('merchandises', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('商品タイプ'); // App\Enums\MerchandiseType
            $table->unsignedBigInteger('product_id')->nullable()->comment('オーダーメイド品ID');
            $table->unsignedBigInteger('item_id')->nullable()->comment('完成品ID');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('item_id')->references('id')->on('items');
            $table->comment('productsとitemsの親テーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandises');
    }
};
