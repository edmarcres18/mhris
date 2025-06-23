<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Payroll extends Model
{
    use HasFactory, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        // Employee relationship and period identifiers
        'employee_id',
        'start_date',
        'end_date',
        'slug',

        // Base salary components
        'gross_salary',
        'net_salary',
        'total_earnings',
        
        // Regular deductions
        'late_deduction',
        'undertime_deduction',
        'absent_deduction',
        'unpaid_leave_hours',

        // Government contributions
        'sss_contribution',
        'pagibig_contribution',
        'philhealth_contribution',
        'tin_contribution',

        // Employer contributions
        'employer_sss_contribution',
        'employer_philhealth_contribution',
        'employer_pagibig_contribution',
        
        // Loans and advances
        'sss_loan',
        'pagibig_loan',
        'cash_advance',
        
        // Additional earnings
        'overtime_pay',
        'overtime_hours',
        'night_premium_pay',
        'night_premium_hours',
        'holiday_hours',
        'holiday_pay',
        'regular_holiday_hours',
        'special_holiday_hours',
        'special_working_holiday_hours',
        
        // Adjustment fields
        'adjustments',         // Salary adjustments
        'allowances',          // Additional allowances
        'other_adjustments',   // Other positive adjustments
        'cash_bond',           // Cash bond deductions
        'other_deduction',     // Other negative adjustments/deductions
        'other_deduction_description', // Description for other deductions

        // Time tracking details
        'late_time',
        'under_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'undertime_deduction' => 'decimal:2',
        'absent_deduction' => 'decimal:2',
        'unpaid_leave_hours' => 'decimal:2',
        'sss_contribution' => 'decimal:2',
        'pagibig_contribution' => 'decimal:2',
        'philhealth_contribution' => 'decimal:2',
        'tin_contribution' => 'decimal:2',
        'employer_sss_contribution' => 'decimal:2',
        'employer_philhealth_contribution' => 'decimal:2',
        'employer_pagibig_contribution' => 'decimal:2',
        'sss_loan' => 'decimal:2',
        'pagibig_loan' => 'decimal:2',
        'cash_advance' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'holiday_hours' => 'decimal:2',
        'holiday_pay' => 'decimal:2',
        'regular_holiday_hours' => 'decimal:2',
        'special_holiday_hours' => 'decimal:2',
        'special_working_holiday_hours' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'adjustments' => 'decimal:2',
        'allowances' => 'decimal:2',
        'other_adjustments' => 'decimal:2',
        'cash_bond' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'night_premium_pay' => 'decimal:2',
        'night_premium_hours' => 'decimal:2',
    ];

    /**
     * Generate a slug when creating a new payroll record
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payroll) {
            $payroll->slug = Str::slug($payroll->employee_id . '-' . $payroll->start_date . '-' . $payroll->end_date);
        });
    }

    /**
     * Get the employee that owns the payroll.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
