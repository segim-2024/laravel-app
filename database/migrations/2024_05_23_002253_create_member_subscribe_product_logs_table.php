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
        Schema::create('member_subscribe_product_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscribe_id')->unsigned()->index()->comment('구독 ID');
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->bigInteger('product_id')->unsigned()->index()->comment('상품 ID');
            $table->bigInteger('card_id')->unsigned()->index()->comment('카드 ID');
            $table->string('type', 30)->comment('유형');
            $table->string('content', 255)->nullable()->comment('내용');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_subscribe_product_logs');
    }
};
