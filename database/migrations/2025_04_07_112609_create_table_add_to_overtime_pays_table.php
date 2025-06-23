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
        Schema::table('overtime_pays', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_pays', function (Blueprint $table) {
            $table->dropColumn('is_read_by_employee');
            $table->dropColumn('is_read_at_employee');
            $table->dropColumn('approved_by_supervisor');
            $table->dropColumn('approved_at_supervisor');
            $table->dropColumn('is_read_by_supervisor');
            $table->dropColumn('is_read_at_supervisor');
            $table->dropColumn('approved_by_finance');
            $table->dropColumn('approved_at_finance');
            $table->dropColumn('is_read_by_finance');
            $table->dropColumn('is_read_at_finance');
            $table->dropColumn('approved_by_vpfinance');
            $table->dropColumn('approved_at_vpfinance');
            $table->dropColumn('is_read_by_vpfinance');
            $table->dropColumn('is_read_at_vpfinance');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_at');
            $table->dropColumn('is_read_by_rejected');
            $table->dropColumn('is_read_at_rejected');
        });
    }
};
