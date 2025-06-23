<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->text('time_in_address')->nullable()->after('time_in');
            $table->text('time_out_address')->nullable()->after('time_out');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('time_in_address');
            $table->dropColumn('time_out_address');
        });
    }
}; 