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
        Schema::create('material_specular_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id')->comment('素材ID');
            $table->string('original_filename')->comment('オリジナルファイル名');
            $table->string('filename')->comment('ファイル名');
            $table->timestamps();

            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_specular_maps');
    }
};
