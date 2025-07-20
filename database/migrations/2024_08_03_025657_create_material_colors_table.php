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
        Schema::create('material_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id')->comment('素材ID');
            $table->string('name')->comment('名称'); // 色 or テクスチャの名前
            $table->string('color_code')->nullable()->comment('色');
            $table->string('original_texture_filename')->nullable()->comment('オリジナル・テクスチャファイル名');
            $table->string('texture_filename')->nullable()->comment('テクスチャファイル名');
            $table->timestamps();

            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_colors');
    }
};
