<?php

namespace App\Models;

use App\Enums\LoanType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class PagibigLoan extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'loan_type',
        'loan_amount',
        'interest_rate',
        'loan_term_months',
        'monthly_amortization',
        'total_accumulated_value', // Add this new field
    ];

    protected $casts = [
        'loan_type' => LoanType::class,
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateMonthlyAmortization(): float
    {
        $P = $this->loan_amount;
        $n = $this->loan_term_months;

        // Determine the interest rate based on the loan type
        if ($this->loan_type === LoanType::HOUSING) {
            $interestRate = $this->determineHousingLoanInterestRate($P);
        } else {
            // Use the provided interest rate or the default from the enum
            $interestRate = $this->interest_rate ?? $this->loan_type->getDefaultInterestRate();
        }

        // Store the determined interest rate
        $this->interest_rate = $interestRate;

        $r = $interestRate / 100 / 12; // Monthly interest rate

        // Use the same formula for all loan types
        return ($P * $r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
    }

    protected function determineHousingLoanInterestRate(): float
    {
        $loanTermYears = $this->loan_term_months / 12;

        if ($loanTermYears <= 1) {
            return 6.375;
        } elseif ($loanTermYears <= 3) {
            return 6.625;
        } elseif ($loanTermYears <= 5) {
            return 6.875;
        } elseif ($loanTermYears <= 10) {
            return 7.125;
        } elseif ($loanTermYears <= 15) {
            return 7.375;
        } elseif ($loanTermYears <= 20) {
            return 7.625;
        } elseif ($loanTermYears <= 25) {
            return 7.875;
        } else {
            return 8.125;
        }
    }

    public function payments()
    {
        return $this->hasMany(PagibigLoanPayment::class);
    }

    public function calculateRemainingBalance(): float
    {
        $totalPaid = $this->payments()->sum('amount');
        return max(0, $this->loan_amount - $totalPaid);
    }

    public function calculateLoanableAmount(): float
    {
        if ($this->loan_type === LoanType::CALAMITY) {
            return $this->total_accumulated_value * 0.8; // 80% of TAV
        }
        // For other loan types, you might want to implement different logic
        return 0;
    }

    public function calculateTotalRepayment(): float
    {
        return $this->monthly_amortization * $this->loan_term_months;
    }

    public function calculateTotalInterest(): float
    {
        return $this->calculateTotalRepayment() - $this->loan_amount;
    }
}
