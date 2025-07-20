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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->comment('ユーザーID');
            $table->string('name')->comment('お名前');
            $table->string('email')->comment('メールアドレス');
            $table->string('contact_subject_type')->comment('お問い合わせ種別'); // App\Enums\ContactSubjectType
            $table->string('order_id')->nullable()->comment('注文ID');
            $table->string('subject')->comment('件名');
            $table->text('body')->comment('お問い合わせ内容');
            $table->boolean('email_settings')->comment('メール設定');
            $table->boolean('confirmed')->comment('確認');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
