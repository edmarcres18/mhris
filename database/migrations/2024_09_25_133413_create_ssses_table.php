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
        Schema::create('ssses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOndelete();
            $table->decimal('monthly_salary_credit', 10, 2);
            $table->decimal('employee_contribution', 8, 2);
            $table->decimal('employer_contribution', 8, 2);
            $table->decimal('total_contribution', 8, 2);
            $table->decimal('ec_contribution', 8, 2)->default(10.00); // Employee Compensation contribution
            $table->date('contribution_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ssses');
    }
};
