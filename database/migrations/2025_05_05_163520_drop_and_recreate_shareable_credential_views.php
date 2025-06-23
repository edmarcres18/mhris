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
        // Drop the existing table if it exists
        Schema::dropIfExists('shareable_credential_views');
        
        // Recreate the table with the correct structure
        Schema::create('shareable_credential_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shareable_credential_link_id');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('email')->nullable();
            $table->string('auth_provider')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->boolean('is_authenticated')->default(false);
            $table->timestamps();
            
            // Foreign key using the correct column name
            $table->foreign('shareable_credential_link_id')
                ->references('id')
                ->on('shareable_credential_links')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shareable_credential_views');
    }
};
