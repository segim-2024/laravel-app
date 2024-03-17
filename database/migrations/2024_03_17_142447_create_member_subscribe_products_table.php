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
        Schema::create('member_subscribe_products', function (Blueprint $table) {
            $table->id();
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->bigInteger('product_id')->unsigned()->index()->comment('상품 ID');
            $table->bigInteger('card_id')->unsigned()->index()->comment('카드 ID');
            $table->timestamp('latest_payment_at')->nullable()->comment('최근 결제일');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('card_id')
                ->references('id')
                ->on('member_cards')
                ->onDelete('cascade');

            $table->unique(['product_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_subscribe_products', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('member_subscribe_products_card_id_foreign');
            $table->dropForeign('member_subscribe_products_product_id_foreign');
        });

        Schema::dropIfExists('member_subscribe_products');
    }
};
