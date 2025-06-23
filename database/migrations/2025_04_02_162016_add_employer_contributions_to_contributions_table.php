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
        Schema::table('contributions', function (Blueprint $table) {
            $table->float('employer_sss_contribution')->nullable()->after('sss_contribution');
            $table->float('employer_philhealth_contribution')->nullable()->after('philhealth_contribution');
            $table->float('employer_pagibig_contribution')->nullable()->after('pagibig_contribution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('employer_sss_contribution');
            $table->dropColumn('employer_philhealth_contribution');
            $table->dropColumn('employer_pagibig_contribution');
        });
    }
};
