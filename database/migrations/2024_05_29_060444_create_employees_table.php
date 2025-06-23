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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->unique();
            $table->string('profile')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('email_address')->unique();
            $table->string('contact_no');
            $table->date('birth_date');
            $table->string('birth_place_province')->nullable();
            $table->string('birth_place_city')->nullable();
            $table->string('birth_place_barangay')->nullable();
            $table->string('province_id')->constrained()->cascadeOnDelete();
            $table->string('city_id')->constrained()->cascadeOnDelete();
            $table->string('department_id')->constrained()->cascadeOnDelete();
            $table->string('barangay_id')->constrained()->cascadeOnDelete();
            $table->char('zip_code');
            $table->string('gender_id')->constrained()->cascadeOnDelete();
            $table->string('position_id')->constrained()->cascadeOnDelete();
            $table->date('date_hired');
            $table->decimal('salary', 10, 2);
            $table->string('sss_no')->nullable();
            $table->string('pagibig_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('philhealth_no')->nullable();
            $table->string('elementary')->nullable();
            $table->string('secondary')->nullable();
            $table->string('tertiary')->nullable();
            $table->string('emergency_name');
            $table->string('emergency_no');
            $table->string('employment_status')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
