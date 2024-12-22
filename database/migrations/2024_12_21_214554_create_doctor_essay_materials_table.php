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
        Schema::create('doctor_essay_materials', function (Blueprint $table) {
            $table->comment('논술 박사 자료');

            $table->id();
            $table->uuid('lesson_uuid')->index()->comment('논술 박사 Lesson UUID');
            $table->uuid('material_uuid')->index()->comment('논술 박사 파일 UUID');
            $table->string('title', 100)->comment('파일 제목');
            $table->string('hex_color_code', 20)->nullable()->comment('컬러 코드');
            $table->string('bg_hex_color_code', 20)->nullable()->comment('배경 코드');
            $table->uuid('file_uuid')->nullable()->comment('파일 UUID');
            $table->timestamps();

            $table->foreign('lesson_uuid')
                ->references('lesson_uuid')
                ->on('doctor_essay_lessons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_essay_materials', function (Blueprint $table) {
            // 외래키 관계를 선언했다면, 리버스 마이그레이션 할때 에러를 피하기 위해
            // 테이블을 삭제하기 전에 외래키를 먼저 삭제하는 것이 중요하다.
            $table->dropForeign('doctor_essay_materials_lesson_uuid_foreign');
        });

        Schema::dropIfExists('doctor_essay_materials');
    }
};
