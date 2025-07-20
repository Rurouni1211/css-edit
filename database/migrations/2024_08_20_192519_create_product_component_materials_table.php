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
        Schema::create('product_component_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('product_component_id')->comment('商品パーツID');
            $table->unsignedBigInteger('material_id')->nullable()->comment('素材ID'); // ロゴは素材がないのでNullを許可
            $table->unsignedInteger('amount')->nullable()->comment('金額');
            $table->boolean('is_active')->default(false)->comment('有効 or 無効');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_component_id')->references('id')->on('product_components');
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_component_materials');
    }
};
