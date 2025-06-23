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
        Schema::table('employees', function (Blueprint $table) {
            // Convert days to seconds
            $table->bigInteger('sick_leave')->default(7 * 24);
            $table->bigInteger('emergency_leave')->default(3 * 24);
            $table->bigInteger('vacation_leave')->default(5 * 24);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('sick_leave');
            $table->dropColumn('emergency_leave');
            $table->dropColumn('vacation_leave');
        });
    }
};
