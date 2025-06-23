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
            $table->enum('approval_status', ['pending', 'approvedBySupervisor', 'rejectedBySupervisor', 'approvedByFinance', 'rejectedByFinance', 'approvedByVPFinance', 'rejectedByVPFinance'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_pays', function (Blueprint $table) {
            $table->dropColumn(['approval_status']);
        });
    }
};
