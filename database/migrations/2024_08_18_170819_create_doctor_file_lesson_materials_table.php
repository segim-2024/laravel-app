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
        Schema::create('doctor_file_lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->uuid('lesson_uuid')->index()->comment('자료박사 Lesson UUID');
            $table->uuid('material_uuid')->index()->comment('자료박사 파일 UUID');
            $table->string('title', 100)->nullable()->comment('파일 제목');
            $table->string('description', 1000)->nullable()->comment('설명');
            $table->string('hex_color_code', 20)->nullable()->comment('컬러코드');
            $table->uuid('file_uuid')->nullable()->comment('파일 UUID');
            $table->timestamps();

            $table->foreign('lesson_uuid')
                ->references('lesson_uuid')
                ->on('doctor_file_lessons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_file_lesson_materials', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('doctor_file_lesson_materials_lesson_uuid_foreign');
        });

        Schema::dropIfExists('doctor_file_lesson_materials');
    }
};
