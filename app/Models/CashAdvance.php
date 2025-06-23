<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CashAdvanceNotification;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class CashAdvance extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'cash_advance_amount',
        'repayment_term',
        'monthly_amortization',
        'total_repayment',
        'status',
        'signature',
        'reference_number',
        'approved_by',
        'rejected_by',
    ];

    protected $casts = [
        'cash_advance_amount' => 'decimal:2',
        'monthly_amortization' => 'decimal:2',
        'total_repayment' => 'decimal:2',
    ];

    /**
     * Get the employee associated with the loan.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Calculate monthly amortization and total repayment.
     */
    public function calculateLoanDetails()
    {
        if ($this->repayment_term > 0) {
            $this->monthly_amortization = $this->cash_advance_amount / $this->repayment_term;
        } else {
            $this->monthly_amortization = 0;
        }
        $this->total_repayment = $this->cash_advance_amount;

        return $this;
    }

    /**
     * Calculate the remaining balance of the cash advance.
     */
    public function remainingBalance()
    {
        $totalPaid = $this->payments()->sum('amount');
        return $this->total_repayment - $totalPaid;
    }

    public function generatePayment()
    {
        if ($this->status === 'active' && $this->remainingBalance() > 0) {
            $paymentAmount = min($this->monthly_amortization, $this->remainingBalance());

            $payment = new CashAdvancePayment([
                'amount' => $paymentAmount,
                'payment_date' => now(),
            ]);

            $this->payments()->save($payment);

            if ($this->remainingBalance() == 0) {
                $this->status = 'complete';
                $this->save();
            }
        }
    }

    public function payments()
    {
        return $this->hasMany(CashAdvancePayment::class);
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedByUser()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
