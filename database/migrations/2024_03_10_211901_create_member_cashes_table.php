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
        Schema::create('member_cashes', function (Blueprint $table) {
            $table->comment('캐쉬 잔액');

            $table->string('member_id', 20)->unique()->comment('학원 유저 ID');
            $table->bigInteger('amount')->default(0)->comment('포인트 잔액');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_cashes', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('member_cashes_member_id_foreign');
        });

        Schema::dropIfExists('member_cashes');
    }
};
