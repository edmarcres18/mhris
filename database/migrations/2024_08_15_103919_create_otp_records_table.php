<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('otp_records', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('otp');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('otp_records');
    }
}

