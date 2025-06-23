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
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('google_user_id');
            $table->unsignedBigInteger('hiring_id');
            $table->timestamps();

            $table->foreign('google_user_id')->references('id')->on('google_users')->onDelete('cascade');
            $table->foreign('hiring_id')->references('id')->on('hirings')->onDelete('cascade');
            $table->unique(['google_user_id', 'hiring_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};
