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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('商品カテゴリ ID');
            $table->string('name')->comment('商品名');
            $table->string('product_code')->comment('商品コード');
            $table->unsignedBigInteger('shop_id')->comment('ショップ ID');
            $table->unsignedInteger('sort_number')->default(0)->comment('並び順');
            $table->string('sketchfab_model_key')->comment('Sketchfab のモデルキー');
            $table->string('material_combination_type')->comment('素材組み合わせタイプ'); // App\Enums\MaterialCombinationType
            $table->timestamps();
            $table->softDeletes();

            $table->unique('product_code');
            $table->foreign('category_id')->references('id')->on('product_categories');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->comment('商品（オーダーメイド品）');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
