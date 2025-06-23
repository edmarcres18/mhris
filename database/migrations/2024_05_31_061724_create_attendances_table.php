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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('time_stamp1')->nullable();
            $table->string('time_stamp2')->nullable();
            $table->date('date_attended');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('remarks')->nullable();
            $table->string('hours_worked')->nullable();
            $table->time('overtime_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
