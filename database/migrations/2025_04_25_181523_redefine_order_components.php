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
        Schema::table('order_components', function (Blueprint $table) {
            $table->dropForeign('order_components_component_id_foreign');
            $table->dropColumn('component_id');
            $table->dropColumn('component_name');
            $table->dropColumn('group_name');

            $table->json('parameter_json')->after('key')->comment('パラメータJSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_components', function (Blueprint $table) {
            $table->unsignedBigInteger('component_id')->after('id');
            $table->string('component_name')->after('component_id');
            $table->string('group_name')->after('component_name');
        });
    }
};
