<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to add default values
        DB::statement('ALTER TABLE overtime_pays MODIFY overtime_hours DECIMAL(8,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE overtime_pays MODIFY overtime_pay DECIMAL(10,2) NOT NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to remove default values
        DB::statement('ALTER TABLE overtime_pays MODIFY overtime_hours DECIMAL(8,2) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE overtime_pays MODIFY overtime_pay DECIMAL(10,2) NULL DEFAULT NULL');
    }
};
