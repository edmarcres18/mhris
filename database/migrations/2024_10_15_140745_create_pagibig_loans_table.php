<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagibig_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('loan_type', ['housing', 'multi_purpose', 'calamity']);
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('loan_term_months');
            $table->decimal('monthly_amortization', 12, 2);
            $table->enum('status', ['active', 'complete'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagibig_loans');
    }
};
