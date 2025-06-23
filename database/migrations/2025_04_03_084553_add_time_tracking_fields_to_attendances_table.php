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
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('late_time')->nullable()->after('overtime_hours');
            $table->string('under_time')->nullable()->after('late_time');
            $table->string('unpaid_leave_time')->nullable()->after('under_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('late_time');
            $table->dropColumn('under_time');
            $table->dropColumn('unpaid_leave_time');
        });
    }
};
