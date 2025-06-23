<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->decimal('holiday_hours', 5, 2)->default(8.0)->after('type');
        });

        // Set default values based on holiday type
        DB::table('holidays')
            ->where('type', '<>', Holiday::TYPE_SPECIAL_WORKING)
            ->update(['holiday_hours' => 8.0]);

        DB::table('holidays')
            ->where('type', Holiday::TYPE_SPECIAL_WORKING)
            ->update(['holiday_hours' => 0.0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('holiday_hours');
        });
    }
};
