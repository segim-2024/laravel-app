<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_whale';

    public function up(): void
    {
        Schema::connection($this->connection)->create('member_subscribe_products', function (Blueprint $table) {
            $table->comment('회원 구독 상품');

            $table->id();
            $table->string('member_id', 20)->index()->comment('회원 ID');
            $table->bigInteger('product_id')->unsigned()->index()->comment('상품 ID');
            $table->bigInteger('card_id')->unsigned()->index()->comment('카드 ID');
            $table->boolean('is_started')->default(false)->comment('서비스 시작 여부');
            $table->boolean('is_activated')->default(true)->comment('활성화 여부');
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

    public function down(): void
    {
        Schema::connection($this->connection)->table('member_subscribe_products', function (Blueprint $table) {
            $table->dropForeign('member_subscribe_products_product_id_foreign');
            $table->dropForeign('member_subscribe_products_card_id_foreign');
        });

        Schema::connection($this->connection)->dropIfExists('member_subscribe_products');
    }
};