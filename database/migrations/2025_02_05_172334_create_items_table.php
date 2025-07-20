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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('カテゴリID');
            $table->string('name')->comment('商品名');
            $table->string('item_code')->comment('商品コード');
            $table->unsignedBigInteger('shop_id')->comment('ショップID');
            $table->unsignedInteger('sort_number')->default(0)->comment('並び順');
            $table->text('description')->comment('説明文');
            $table->unsignedInteger('price')->comment('価格');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shop_id')->references('id')->on('shops');
            $table->foreign('category_id')->references('id')->on('item_categories');
            $table->comment('商品（完成品）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
