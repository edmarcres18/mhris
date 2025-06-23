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
        Schema::create('shareable_email_links', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->text('description')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('company_email_shareable_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_email_id')->constrained('company_emails')->onDelete('cascade');
            $table->foreignId('shareable_email_link_id')->constrained('shareable_email_links')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_email_shareable_link');
        Schema::dropIfExists('shareable_email_links');
    }
};
