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
            $table->decimal('holiday_hours', 8, 2)->default(0)->after('overtime_hours');
            $table->decimal('holiday_pay', 12, 2)->default(0)->after('holiday_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('holiday_hours');
            $table->dropColumn('holiday_pay');
        });
    }
};
