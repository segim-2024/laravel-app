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
        Schema::create('doctor_file_lessons', function (Blueprint $table) {
            $table->id();
            $table->uuid('lesson_uuid')->unique()->comment('자료박사 Lesson UUID');
            $table->uuid('series_uuid')->index()->comment('자료박사 Series UUID');
            $table->uuid('volume_uuid')->index()->comment('자료박사 Volume UUID');
            $table->string('title', 50)->comment('제목');
            $table->integer('sort')->unsigned()->default(0)->comment('정렬 순서');
            $table->boolean('is_whale')->default(false)->comment('고래영어 여부');
            $table->timestamps();

            $table->comment('자료박사 Lessons');

            $table->foreign('series_uuid')
                ->references('series_uuid')
                ->on('doctor_file_series')
                ->onDelete('cascade');

            $table->foreign('volume_uuid')
                ->references('volume_uuid')
                ->on('doctor_file_volumes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_file_lessons', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('doctor_file_lessons_series_uuid_foreign');
            $table->dropForeign('doctor_file_lessons_volume_uuid_foreign');
        });

        Schema::dropIfExists('doctor_file_lessons');
    }
};
