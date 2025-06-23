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
        Schema::table('hirings', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('employment_type')->nullable();
            $table->text('responsibilities')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hirings', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'employment_type', 'responsibilities']);
        });
    }
};
