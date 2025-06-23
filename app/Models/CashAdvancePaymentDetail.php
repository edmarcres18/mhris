<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class CashAdvancePaymentDetail extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'cash_advance_payment_id',
        'amount',
        'payment_date',
        'covered_period_start',
        'covered_period_end',
        'notes',
        'payment_period',
        'loan_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'covered_period_start' => 'date',
        'covered_period_end' => 'date',
    ];

    /**
     * Get the cash advance payment that this detail belongs to.
     */
    public function cashAdvancePayment()
    {
        return $this->belongsTo(CashAdvancePayment::class);
    }

    /**
     * Get the loan record associated with this payment detail.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
