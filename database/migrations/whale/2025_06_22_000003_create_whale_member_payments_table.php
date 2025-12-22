<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_whale';

    public function up(): void
    {
        Schema::connection($this->connection)->create('member_payments', function (Blueprint $table) {
            $table->comment('회원 결제 내역');

            $table->id();
            $table->string('payment_id')->unique()->comment('주문 UUID');
            $table->string('member_id', 20)->index()->comment('회원 ID');
            $table->bigInteger('card_id')->nullable()->unsigned()->comment('카드 ID');
            $table->string('state')->default('UNPAID')->index()->comment('결제 상태');
            $table->string('method', 20)->nullable()->comment('결제 수단');
            $table->nullableMorphs('productable');
            $table->string('title')->comment('결제 제목');
            $table->integer('amount')->unsigned()->comment('결제 금액');
            $table->integer('cancelled_amount')->nullable()->unsigned()->comment('취소 금액');
            $table->string('reason')->nullable()->comment('결제 취소/실패 사유');
            $table->mediumText('api')->nullable()->comment('결제 응답 데이터');
            $table->mediumText('receipt_url')->nullable()->comment('영수증 URL');
            $table->string('payment_key')->nullable()->comment('PG사 결제키');
            $table->timestamp('paid_at')->nullable()->comment('결제 일시');
            $table->timestamp('cancelled_at')->nullable()->comment('취소 일시');
            $table->timestamps();

            $table->foreign('card_id')
                ->references('id')
                ->on('member_cards')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('member_payments', function (Blueprint $table) {
            $table->dropForeign('member_payments_card_id_foreign');
        });

        Schema::connection($this->connection)->dropIfExists('member_payments');
    }
};