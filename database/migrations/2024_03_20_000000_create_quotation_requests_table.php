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
        Schema::create('quotation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('medical_products')->onDelete('cascade');
            $table->string('product_name');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('hospital_name');
            $table->enum('status', ['pending', 'processed', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_requests');
    }
}; 