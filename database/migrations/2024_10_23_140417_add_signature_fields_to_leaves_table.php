<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('signature')->nullable();
            $table->string('approved_by_signature')->nullable();
            $table->string('validated_by_signature')->nullable();
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn([
                'signature',
                'approved_by_signature',
                'validated_by_signature'
            ]);
        });
    }
};
