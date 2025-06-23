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
        Schema::create('google_users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->json('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_users');
    }
};
