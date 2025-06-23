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
        Schema::table('payrolls', function (Blueprint $table) {
            // Adding late_time and under_time columns as string (HH:MM format)
            $table->string('late_time')->default('00:00')->after('absent_deduction');
            $table->string('under_time')->default('00:00')->after('late_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('late_time');
            $table->dropColumn('under_time');
        });
    }
};
