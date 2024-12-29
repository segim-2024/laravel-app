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
        Schema::create('doctor_essay_volumes', function (Blueprint $table) {
            $table->comment('논술 박사 Volume');

            $table->id();
            $table->uuid('volume_uuid')->unique()->comment('논술 박사 Volume UUID');
            $table->uuid('series_uuid')->index()->comment('논술 박사 Series UUID');
            $table->string('title', 50)->comment('제목');
            $table->uuid('poster_image_uuid')->nullable()->comment('표지 이미지');
            $table->string('description', 1000)->nullable()->comment('설명');
            $table->integer('sort')->unsigned()->default(0)->comment('정렬 순서');
            $table->boolean('is_published')->default(false)->comment('공개 여부');
            $table->boolean('is_whale')->default(false)->comment('고래영어 여부');
            $table->timestamps();

            $table->foreign('series_uuid')
                ->references('series_uuid')
                ->on('doctor_essay_series')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_essay_volumes', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('doctor_essay_volumes_series_uuid_foreign');
        });

        Schema::dropIfExists('doctor_essay_volumes');
    }
};
