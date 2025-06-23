<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class SssLoan extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'loan_amount',
        'repayment_term',
        'monthly_amortization',
        'total_repayment',
        'status',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
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
     * Get the payments for the loan.
     */
    public function payments()
    {
        return $this->hasMany(LoanPayment::class, 'loan_id');
    }

    /**
     * Calculate the remaining balance of the loan.
     */
    public function getRemainingBalanceAttribute()
    {
        $totalPaid = $this->payments->sum('amount');
        return $this->total_repayment - $totalPaid;
    }
}
