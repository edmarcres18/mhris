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
        Schema::table('cash_advance_payments', function (Blueprint $table) {
            $table->date('covered_period_start')->nullable()->after('payment_date');
            $table->date('covered_period_end')->nullable()->after('covered_period_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_advance_payments', function (Blueprint $table) {
            $table->dropColumn(['covered_period_start', 'covered_period_end']);
        });
    }
};
