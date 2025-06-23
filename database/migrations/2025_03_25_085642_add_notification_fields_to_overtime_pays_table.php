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
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_view')->default(false);
            $table->timestamp('view_at')->nullable();
            $table->text('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_pays', function (Blueprint $table) {
            $table->dropColumn('is_read');
            $table->dropColumn('read_at');
            $table->dropColumn('is_view');
            $table->dropColumn('view_at');
            $table->dropColumn('rejection_reason');
        });
    }
};
