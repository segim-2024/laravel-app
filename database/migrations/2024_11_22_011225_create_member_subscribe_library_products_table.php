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
        Schema::create('library_product_subscribes', function (Blueprint $table) {
            $table->comment('라이브러리 상품 구독 정보');

            $table->id();
            $table->string('member_id', 20)->unique()->comment('학원 유저 ID');
            $table->bigInteger('product_id')->unsigned()->index()->comment('상품 ID');
            $table->bigInteger('card_id')->unsigned()->index()->comment('카드 ID');
            $table->string("state", 30)->default('pending')->comment('구독 상태');
            $table->date('start')->nullable()->comment('결제 시작일');
            $table->date('end')->nullable()->comment('결제 종료일');
            $table->date('due_date')->nullable()->comment('결제 예정일');
            $table->integer('payment_day')->unsigned()->comment('약정일');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('library_products')
                ->onDelete('cascade');

            $table->foreign('card_id')
                ->references('id')
                ->on('member_cards')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library_product_subscribes', function (Blueprint $table) {
            $table->dropForeign('library_product_subscribes_card_id_foreign');
            $table->dropForeign('library_product_subscribes_product_id_foreign');
        });

        Schema::dropIfExists('library_product_subscribes');
    }
};
