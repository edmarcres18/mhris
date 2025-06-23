<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class LoanPayment extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'loan_id',
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the loan that the payment belongs to.
     */
    public function loan()
    {
        return $this->belongsTo(SssLoan::class, 'loan_id');
    }
}
