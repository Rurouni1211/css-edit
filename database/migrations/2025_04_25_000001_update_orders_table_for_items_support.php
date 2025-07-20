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
        Schema::table('orders', function (Blueprint $table) {
            // 注文タイプを追加（カスタマイズ品か完成品か）
            $table->string('order_type')->after('status')->comment('注文タイプ'); // App\Enums\OrderType
            
            // product_idとproduct_nameを変更しnullを許可
            $table->unsignedBigInteger('product_id')->nullable()->change()->comment('製品ID');
            $table->string('product_name')->nullable()->change()->comment('製品名');
            
            // item_idとitem_nameを追加
            $table->unsignedBigInteger('item_id')->nullable()->after('product_id')->comment('完成品ID');
            $table->string('item_name')->nullable()->after('product_name')->comment('完成品名');
            
            // items外部キー参照を追加
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 外部キー制約を削除
            $table->dropForeign(['item_id']);
            
            // カラムを削除
            $table->dropColumn(['order_type', 'item_id', 'item_name']);
            
            // 元の制約に戻す
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
            $table->string('product_name')->nullable(false)->change();
        });
    }
};