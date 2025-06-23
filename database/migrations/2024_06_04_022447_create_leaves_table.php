<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->string('leave_type')->default('Leave'); // Default value set to 'Leave'
            $table->foreignId('type_id')->nullable();
            $table->string('reason_to_leave');
            $table->string('approved_by')->nullable();
            $table->string('status')->default('pending'); // Assuming 'pending' is the default status
            $table->string('payment_status')->nullable();
            $table->timestamps();
        });

        // Check if 'types' table exists before adding foreign key constraint
        if (Schema::hasTable('types')) {
            Schema::table('leaves', function (Blueprint $table) {
                $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};
