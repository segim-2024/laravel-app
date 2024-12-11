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
        Schema::create('library_payment_api_logs', function (Blueprint $table) {
            $table->comment('라이브러리 결제 API 로그');

            $table->id();
            $table->uuid('payment_id')->unique()->comment('결제 ID');
            $table->boolean('is_success')->default(false)->comment('성공 여부');
            $table->string('message', 100)->nullable()->comment('메시지');
            $table->string('status_code', 5)->nullable()->comment('응답 코드');
            $table->json('data')->nullable()->comment('응답 본문');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_payment_api_logs');
    }
};
