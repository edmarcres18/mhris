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
        Schema::table('overtime_pays', function (Blueprint $table) {
            $table->dateTime('time_in')->nullable()->after('date');
            $table->dateTime('time_out')->nullable()->after('time_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_pays', function (Blueprint $table) {
            $table->dropColumn(['time_in', 'time_out']);
        });
    }
};
