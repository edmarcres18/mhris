<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class CashAdvancePayment extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'cash_advance_id',
        'amount',
        'payment_date',
        'notes',
        'covered_period_start',
        'covered_period_end',
    ];

    protected $dates = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'covered_period_start' => 'date',
        'covered_period_end' => 'date',
    ];

    public function cashAdvance()
    {
        return $this->belongsTo(CashAdvance::class);
    }

    /**
     * Get the bimonthly payment details for this payment.
     */
    public function paymentDetails()
    {
        return $this->hasMany(CashAdvancePaymentDetail::class);
    }

    /**
     * Generate bimonthly payment details.
     */
    public function generateBimonthlyDetails()
    {
        if ($this->covered_period_start && $this->covered_period_end) {
            // Calculate the midpoint of the covered period
            $midPoint = clone $this->covered_period_start;
            $daysBetween = $this->covered_period_start->diffInDays($this->covered_period_end);
            $midPoint->addDays((int)($daysBetween / 2));

            // First half period
            $firstHalfStart = clone $this->covered_period_start;
            $firstHalfEnd = clone $midPoint;
            $firstHalfEnd->subDay();

            // Second half period
            $secondHalfStart = clone $midPoint;
            $secondHalfEnd = clone $this->covered_period_end;

            // Half amount
            $halfAmount = $this->amount / 2;

            // Create the payment details
            $this->paymentDetails()->create([
                'amount' => $halfAmount,
                'payment_date' => $firstHalfEnd,
                'covered_period_start' => $firstHalfStart,
                'covered_period_end' => $firstHalfEnd,
                'notes' => 'First half payment',
                'payment_period' => 'first_half',
            ]);

            $this->paymentDetails()->create([
                'amount' => $halfAmount,
                'payment_date' => $secondHalfEnd,
                'covered_period_start' => $secondHalfStart,
                'covered_period_end' => $secondHalfEnd,
                'notes' => 'Second half payment',
                'payment_period' => 'second_half',
            ]);
        }
    }
}
