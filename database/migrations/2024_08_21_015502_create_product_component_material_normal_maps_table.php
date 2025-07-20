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
        Schema::create('product_component_material_normal_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->unsignedBigInteger('product_component_material_id')->comment('商品構成パーツ素材ID');
            $table->unsignedBigInteger('material_normal_map_id')->comment('素材法線マップID');
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();

            $table->foreign('product_id', 'pcmnm_product_id_fk')->references('id')->on('products');
            $table->foreign('product_component_material_id', 'pcmnm_material_id_fk')->references('id')->on('product_component_materials');
            $table->foreign('material_normal_map_id', 'pcmnm_normal_map_id_fk')->references('id')->on('material_normal_maps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_component_material_normal_maps');
    }
};
