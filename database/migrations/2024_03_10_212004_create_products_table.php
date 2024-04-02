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
        Schema::create('products', function (Blueprint $table) {
            $table->comment('결제 상품');

            $table->id();
            $table->string('name', 100)->comment("상품명");
            $table->string('payment_day', 10)->comment("결제 일");
            $table->integer('price')->comment("결제 금액");
            $table->boolean('is_used')->default(true)->comment("사용 여부");
            $table->boolean('is_deleted')->default(false)->comment("삭제 여부");
            $table->timestamp('deleted_at')->nullable()->comment("삭제 여부");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
