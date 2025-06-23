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
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->decimal('cash_advance_amount', 10, 2);
            $table->integer('repayment_term');
            $table->decimal('monthly_amortization', 10, 2);
            $table->decimal('total_repayment', 10, 2);
            $table->enum('status', ['pending', 'active', 'declined', 'complete'])->default('pending');
            $table->string('signature')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamps(); // This will add created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advances');
    }
};
