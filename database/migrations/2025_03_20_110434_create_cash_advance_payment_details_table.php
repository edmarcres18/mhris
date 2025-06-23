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
        Schema::create('cash_advance_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_advance_payment_id')->constrained('cash_advance_payments')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->date('covered_period_start');
            $table->date('covered_period_end');
            $table->string('notes')->nullable();
            $table->enum('payment_period', ['first_half', 'second_half']);
            $table->foreignId('loan_id')->nullable()->constrained('loans')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_advance_payment_details');
    }
};
