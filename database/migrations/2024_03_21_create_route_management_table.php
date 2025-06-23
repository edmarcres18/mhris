<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('route_management', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique();
            $table->string('route_path');
            $table->string('method');
            $table->string('controller');
            $table->string('action');
            $table->string('middleware')->nullable();
            $table->string('type')->default('web'); // web or api
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_management');
    }
}; 