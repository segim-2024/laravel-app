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
        Schema::create('order_segim_ticket_minus_logs', function (Blueprint $table) {
            $table->id();
            $table->string("mb_id", 255)->comment("회원 ID");
            $table->morphs('cartable');
            $table->integer("qty")->unsigned()->comment("반품 수량");
            $table->string("it_id", 255)->comment("상품 ID");
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
        Schema::dropIfExists('order_segim_ticket_minus_logs');
    }
};
