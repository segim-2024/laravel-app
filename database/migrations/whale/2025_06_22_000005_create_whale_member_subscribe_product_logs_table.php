<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_whale';

    public function up(): void
    {
        Schema::connection($this->connection)->create('member_subscribe_product_logs', function (Blueprint $table) {
            $table->comment('회원 구독 상품 로그');

            $table->id();
            $table->bigInteger('subscribe_id')->unsigned()->index()->comment('구독 ID');
            $table->string('type')->comment('로그 타입');
            $table->text('message')->nullable()->comment('로그 메시지');
            $table->timestamps();

            $table->foreign('subscribe_id')
                ->references('id')
                ->on('member_subscribe_products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('member_subscribe_product_logs', function (Blueprint $table) {
            $table->dropForeign('member_subscribe_product_logs_subscribe_id_foreign');
        });

        Schema::connection($this->connection)->dropIfExists('member_subscribe_product_logs');
    }
};