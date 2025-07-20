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
        Schema::create('product_component_material_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('product_component_material_id')->comment('商品構成パーツ素材ID');
            $table->unsignedBigInteger('material_color_id')->comment('素材カラーID');
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();

            $table->foreign('product_id', 'pcmc_product_id_fk')->references('id')->on('products');
            $table->foreign('product_component_material_id', 'pcmc_material_id_fk')->references('id')->on('product_component_materials');
            $table->foreign('material_color_id', 'pcmc_color_id_fk')->references('id')->on('material_colors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_component_material_colors');
    }
};
