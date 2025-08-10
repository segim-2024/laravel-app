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
        Schema::create('premium_purchase_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 20)->index();
            $table->string('member_id', 20)->index();
            $table->string('status')->default('pending')->comment('승인 상태: pending, approved, rejected');
            $table->mediumText('request_reason')->nullable()->comment('승인 요청 사유');
            $table->timestamps();

            // 인덱스 추가
            $table->index(['product_id', 'member_id']);
            $table->index(['member_id', 'status']);
            $table->index(['product_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_purchase_approvals');
    }
};
