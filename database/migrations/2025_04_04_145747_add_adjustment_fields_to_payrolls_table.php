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
            $table->decimal('adjustments', 10, 2)->default(0)->after('overtime_pay')->comment('Salary adjustments');
            $table->decimal('allowances', 10, 2)->default(0)->after('adjustments')->comment('Employee allowances');
            $table->decimal('other_adjustments', 10, 2)->default(0)->after('allowances')->comment('Other salary adjustments');
            $table->decimal('cash_bond', 10, 2)->default(0)->after('other_adjustments')->comment('Cash bond deductions');
            $table->decimal('other_deduction', 10, 2)->default(0)->after('cash_bond')->comment('Other salary deductions');
            $table->decimal('employer_sss_contribution', 10, 2)->default(0)->after('other_deduction')->comment('Employer SSS contribution');
            $table->decimal('employer_philhealth_contribution', 10, 2)->default(0)->after('employer_sss_contribution')->comment('Employer Philhealth contribution');
            $table->decimal('employer_pagibig_contribution', 10, 2)->default(0)->after('employer_philhealth_contribution')->comment('Employer Pagibig contribution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'adjustments',
                'allowances',
                'other_adjustments',
                'cash_bond',
                'other_deduction'
            ]);
        });
    }
}; 