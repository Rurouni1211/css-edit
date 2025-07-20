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
        Schema::create('item_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->comment('商品（完成品）ID');
            $table->string('filename')->comment('ファイル名');
            $table->unsignedInteger('sort_number')->comment('表示順');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items');
            $table->comment('商品（完成品）画像');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_images');
    }
};
