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
        Schema::create('library_products', function (Blueprint $table) {
            $table->comment('라이브러리 결제 상품');

            $table->id();
            $table->string('name')->comment('상품명');
            $table->integer('price')->unsigned()->comment('상품 금액');
            $table->integer('ticket_provide_qty')->unsigned()->comment('이용권 제공 수량');
            $table->boolean('is_hided')->default(false)->comment('숨김 처리 여부');
            $table->timestamp('deleted_at')->default(null)->nullable()->comment('삭제 일시');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_products');
    }
};
