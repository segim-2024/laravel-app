<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_whale';

    public function up(): void
    {
        Schema::connection($this->connection)->table('member_payments', function (Blueprint $table) {
            $table->string('tx_id')->index()->nullable()->comment('PG사 거래 고유 키')->after('payment_id');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('member_payments', function (Blueprint $table) {
            $table->dropColumn('tx_id');
        });
    }
};