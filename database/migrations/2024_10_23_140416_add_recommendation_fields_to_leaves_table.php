<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('recommendation_status')->nullable();
            $table->string('approved_days')->nullable();
            $table->text('disapproval_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'recommendation_status',
                'approved_days',
                'disapproval_reason'
            ]);
        });
    }
};
