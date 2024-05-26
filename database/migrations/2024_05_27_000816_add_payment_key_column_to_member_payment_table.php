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
        Schema::table('member_payments', function (Blueprint $table) {
            $table->string('toss_key')->index()->nullable()->comment('토스 고유 키')->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_payments', function (Blueprint $table) {
            $table->dropColumn('toss_key');
        });
    }
};
