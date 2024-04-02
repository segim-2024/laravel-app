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
        Schema::create('member_cash_transactions', function (Blueprint $table) {
            $table->comment('캐쉬 거래 내역');

            $table->id();
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->string('transactionable_type')->nullable();
            $table->bigInteger('transactionable_id')->unsigned()->nullable();
            $table->string('type')->comment('유형(증가, 감소)');
            $table->string('title')->comment('제목');
            $table->bigInteger('amount')->default(0)->comment('포인트 금액');
            $table->timestamps();

            $table->index(['transactionable_type', 'transactionable_id'], 'transactionable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_cash_transactions');
    }
};
