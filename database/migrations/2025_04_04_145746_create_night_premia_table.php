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
        Schema::create('night_premiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->dateTime('time_in');
            $table->dateTime('time_out');
            $table->decimal('night_hours', 8, 2)->default(0);
            $table->decimal('night_rate', 8, 2)->default(1.10);
            $table->decimal('night_premium_pay', 10, 2)->default(0);
            $table->enum('approval_status', ['pending', 'approvedBySupervisor', 'rejectedBySupervisor', 'approvedByFinance', 'rejectedByFinance', 'approvedByVPFinance', 'rejectedByVPFinance'])->default('pending');

            $table->boolean('is_read_by_employee')->default(false);
            $table->dateTime('is_read_at_employee')->nullable();
            // Supervisor approval
            $table->foreignId('approved_by_supervisor')->nullable()->constrained('users');
            $table->dateTime('approved_at_supervisor')->nullable();
            $table->boolean('is_read_by_supervisor')->default(false);
            $table->dateTime('is_read_at_supervisor')->nullable();
            // Finance Head approval
            $table->foreignId('approved_by_finance')->nullable()->constrained('users');
            $table->dateTime('approved_at_finance')->nullable();
            $table->boolean('is_read_by_finance')->default(false);
            $table->dateTime('is_read_at_finance')->nullable();
            // VP Finance approval
            $table->foreignId('approved_by_vpfinance')->nullable()->constrained('users');
            $table->dateTime('approved_at_vpfinance')->nullable();
            $table->boolean('is_read_by_vpfinance')->default(false);
            $table->dateTime('is_read_at_vpfinance')->nullable();

            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->dateTime('rejected_at')->nullable();
            $table->boolean('is_read_by_rejected')->default(false);
            $table->dateTime('is_read_at_rejected')->nullable();
            $table->text('rejection_reason')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('night_premiums');
    }
};
