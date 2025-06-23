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
        Schema::create('philhealths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOndelete();
            $table->decimal('employee_contribution', 8, 2)->nullable();
            $table->decimal('employer_contribution', 8, 2)->nullable();
            $table->decimal('total_contribution', 8, 2)->nullable();
            $table->date('contribution_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('philhealths');
    }
};
