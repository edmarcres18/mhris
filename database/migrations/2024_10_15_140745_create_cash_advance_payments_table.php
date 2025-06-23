<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAdvancePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('cash_advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_advance_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_advance_payments');
    }
}
