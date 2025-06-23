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
        Schema::create('shareable_credential_links', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->text('description')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('credential_shareable_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credentials_id')->constrained('credentials')->onDelete('cascade');
            $table->foreignId('shareable_credential_link_id')->constrained('shareable_credential_links')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credential_shareable_link');
        Schema::dropIfExists('shareable_credential_links');
    }
}; 