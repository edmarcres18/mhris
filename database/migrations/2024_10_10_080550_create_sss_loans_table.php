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
        Schema::create('sss_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('loan_amount', 10, 2); // Loan amount input
            $table->integer('repayment_term'); // Number of months for repayment
            $table->decimal('monthly_amortization', 10, 2); // Computed monthly amortization
            $table->decimal('total_repayment', 10, 2); // Computed total repayment
            $table->enum('status', ['active', 'complete'])->default('active');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sss_loans');
    }
};
