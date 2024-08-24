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
        Schema::create('doctor_file_notices', function (Blueprint $table) {
            $table->id();
            $table->string('member_id', 20)->index()->comment('학원 유저 ID');
            $table->string('title', 100)->comment('제목');
            $table->mediumText('content')->comment('내용');
            $table->boolean('is_whale')->default(false)->comment('고래 영어 여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_file_notices');
    }
};
