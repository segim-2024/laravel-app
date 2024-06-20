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
        Schema::create('member_payments', function (Blueprint $table) {
            $table->comment('회원 결제 내역');

            $table->id();
            $table->string('payment_id')->unique()->comment('주문 UUID');
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->bigInteger('card_id')->nullable()->unsigned()->comment('유저 ID');
            $table->string('state')->default('UNPAID')->index()->comment('결제 상태');
            $table->string('method', 20)->nullable()->comment('결제 상태');
            $table->nullableMorphs('productable');
            $table->string('title')->comment('결제 제목');
            $table->integer('amount')->unsigned()->comment('결제 제목');
            $table->integer('cancelled_amount')->nullable()->unsigned()->comment('결제 제목');
            $table->string('reason')->nullable()->comment('결제 취소/실패 사유');
            $table->mediumText('api')->nullable()->comment('결제 응답 데이터');
            $table->mediumText('receipt_url')->nullable()->comment('영수증 URL');
            $table->timestamp('paid_at')->nullable()->comment('결제 제목');
            $table->timestamp('cancelled_at')->nullable()->comment('결제 제목');
            $table->timestamps();

            $table->foreign('card_id')
                ->references('id')
                ->on('member_cards')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_payments', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('member_payments_card_id_foreign');
        });

        Schema::dropIfExists('member_payments');
    }
};
