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
            $table->decimal('regular_holiday_hours', 8, 2)->nullable()->after('holiday_pay')->default(0);
            $table->decimal('special_holiday_hours', 8, 2)->nullable()->after('regular_holiday_hours')->default(0);
            $table->decimal('special_working_holiday_hours', 8, 2)->nullable()->after('special_holiday_hours')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('regular_holiday_hours');
            $table->dropColumn('special_holiday_hours');
            $table->dropColumn('special_working_holiday_hours');
        });
    }
};
