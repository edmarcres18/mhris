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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('gross_salary', 10, 2)->nullable();
            $table->decimal('net_salary', 10,2)->nullable();
            $table->decimal('late_deduction', 8, 2)->default(0.02);
            $table->decimal('undertime_deduction', 8, 2)->default(0.02);
            $table->decimal('absent_deduction')->nullable();
            $table->decimal('sss_contribution')->nullable();
            $table->decimal('pagibig_contribution')->nullable();
            $table->decimal('philhealth_contribution')->nullable();
            $table->decimal('tin_contribution')->nullable();
            $table->decimal('sss_loan')->nullable();
            $table->decimal('pagibig_loan')->nullable();
            $table->decimal('cash_advance')->nullable();
            $table->decimal('total_earnings')->nullable();
            $table->decimal('overtime_pay')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
