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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique()->comment('파일 UUID');
            $table->string('extension', 10)->comment('파일 확장자');
            $table->string('server_name', 50)->comment('서버 파일명');
            $table->string('original_name')->comment('원본 파일명');
            $table->string('full_path', 300)->comment('파일 전체 경로');
            $table->string('path', 200)->comment('파일 경로');
            $table->bigInteger('size')->unsigned()->default(0)->comment('파일 크기');
            $table->timestamps();

            $table->comment('자료 - File');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
