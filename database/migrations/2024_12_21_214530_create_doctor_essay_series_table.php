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
        Schema::create('doctor_essay_series', function (Blueprint $table) {
            $table->comment('논술 박사 Series');

            $table->id();
            $table->uuid('series_uuid')->unique()->comment('논술 박사 Series UUID');
            $table->string('title', 50)->comment('논술 박사 Series 제목');
            $table->integer('sort')->unsigned()->default(0)->comment('정렬 순서');
            $table->boolean('is_whale')->default(false)->comment('고래영어 여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_essay_series');
    }
};
