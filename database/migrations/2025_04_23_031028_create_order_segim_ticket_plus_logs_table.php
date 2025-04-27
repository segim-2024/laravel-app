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
        Schema::create('order_segim_ticket_plus_logs', function (Blueprint $table) {
            $table->id();
            $table->string("mb_id", 255)->comment("회원 ID");
            $table->string("od_id", 255)->comment("주문번호");
            $table->string("it_id", 255)->comment("상품코드");
            $table->string("ct_id", 255)->comment("장바구니 ID(주문 상품 ID)");
            $table->integer("ct_qty")->unsigned()->comment("주문 수량");
            $table->string("ticket_type", 20)->comment("이용권 유형");
            $table->mediumText("api")->nullable()->comment("API 로그");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_segim_ticket_plus_logs');
    }
};
