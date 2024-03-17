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
        Schema::create('member_cards', function (Blueprint $table) {
            $table->comment('정기 결제 카드');

            $table->id();
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->string('name')->comment('카드 이름');
            $table->string('number', 50)->comment('마스킹 카드 번호');
            $table->string('key')->unique()->comment('빌링키');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_cards');
    }
};
