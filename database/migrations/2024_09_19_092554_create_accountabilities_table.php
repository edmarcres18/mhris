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
        Schema::create('accountabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('accountability_it_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accountability_id');
            $table->unsignedBigInteger('it_inventory_id');
            $table->timestamp('assigned_at');
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('accountability_id')->references('id')->on('accountabilities')->onDelete('cascade');
            $table->foreign('it_inventory_id')->references('id')->on('it_inventories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accountability_it_inventory');
        Schema::dropIfExists('accountabilities');
    }
};
