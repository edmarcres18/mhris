<?php

namespace App\Services;

use App\Models\Contribution;
use App\Models\Loan;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\OvertimePay;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\NightPremium;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PayrollService
{
    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function calculatePayroll($employee_id, $start_date, $end_date)
    {
        // Fetch employee data
        $employee = Employee::find($employee_id);
        if (!$employee) {
            return null;
        }

        // Initialize dates
        $start = Carbon::parse($start_date)->startOfDay();
        $end = Carbon::parse($end_date)->endOfDay();

        // Different calculation logic based on department
        if ($employee->department->name === "BGPDI") {
            // For BGPDI: Weekly salary calculation
            $daily_salary = $employee->salary / 26;
            $working_days = $this->calculateWorkingDays($employee);
            $gross_salary = $daily_salary * $working_days;

            // Adjust contributions for weekly payment (divide monthly contributions by 4)
            $contributions = Contribution::where('employee_id', $employee_id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('date', [$start, $end])
                        ->orWhereMonth('date', $start->month)
                        ->orWhereMonth('date', $end->month);
                })
                ->orderBy('date', 'desc')
                ->first();

            if (!$contributions) {
                // If no contributions found for current period, get the most recent one
                $contributions = Contribution::where('employee_id', $employee_id)
                    ->orderBy('date', 'desc')
                    ->first();
            }

            // Get all loans for the pay period for BGPDI employees too
            $loans = Loan::where('employee_id', $employee_id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('date', [$start, $end]);
                })
                ->get();
        } else {
            // Original bi-monthly calculation for non-BGPDI employees
            if ($start->day == 26 && $start->daysInMonth == 31) {
                $end->subDay();
            }

            $daily_salary = $employee->salary / 26;
            $working_days = $this->calculateWorkingDays($employee);
            $gross_salary = $daily_salary * $working_days;

            // Regular contributions and loans (unchanged)
            $contributions = Contribution::where('employee_id', $employee_id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('date', [$start, $end])
                        ->orWhereMonth('date', $start->month)
                        ->orWhereMonth('date', $end->month);
                })
                ->orderBy('date', 'desc')
                ->first();

            if (!$contributions) {
                // If no contributions found for current period, get the most recent one
                $contributions = Contribution::where('employee_id', $employee_id)
                    ->orderBy('date', 'desc')
                    ->first();
            }

            // Instead of fetching a single loan record, get all loans for the pay period
            $loans = Loan::where('employee_id', $employee_id)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('date', [$start, $end]);
                })
                ->get();
        }

        // Fetch attendance records within the date range
        $attendances = Attendance::where('employee_id', $employee_id)
            ->whereBetween('date_attended', [$start, $end])
            ->get();

        $attendancesAbsent = Attendance::where('employee_id', $employee_id)
            ->whereBetween('date_attended', [$start, $end])
            ->where('remarks', 'Absent')
            ->count();

        // Initialize deductions
        $total_deductions = 0;
        $absent_deduction = $attendancesAbsent * $daily_salary;
        $late_deduction = 0;
        $undertime_deduction = 0;
        $overtime_pay = 0;
        $night_premium_pay = 0;
        $late_time_total = '00:00'; // Initialize late time total
        $under_time_total = '00:00'; // Initialize under time total

        foreach ($attendances as $attendance) {
            $remarks = $attendance->remarks;

            // Calculate late and undertime deductions
            if ($remarks === 'Late') {
                $standard_start = Carbon::parse('08:00:00');
                $time_in = Carbon::parse($attendance->time_in);

                if ($time_in && $time_in->gt($standard_start)) {
                    $late_minutes = $time_in->diffInMinutes($standard_start);
                    $late_deduction += $late_minutes * $this->deductionPerMinute($employee);
                    
                    // Get or calculate late_time
                    $late_time_total = $this->addTimes($late_time_total, $attendance->late_time ?? $attendance->calculateLateTime());
                }
            }

            if ($remarks === 'UnderTime') {
                $standard_end = Carbon::parse('17:00:00');
                $time_out = Carbon::parse($attendance->time_out);

                if ($time_out && $time_out->lt($standard_end)) {
                    $undertime_minutes = $standard_end->diffInMinutes($time_out);
                    $undertime_deduction += $undertime_minutes * $this->deductionPerMinute($employee);
                    
                    // Get or calculate under_time
                    $under_time_total = $this->addTimes($under_time_total, $attendance->under_time ?? $attendance->calculateUnderTime());
                }
            }

            if ($remarks === 'Absent') {
                $absent_deduction += $daily_salary;
            }
        }

        // Fetch Overtime Pay records and calculate total overtime pay
        $overtime_pay = OvertimePay::getTotalOvertimePay($employee_id, $start_date, $end_date);
        $overtime_hours = OvertimePay::getTotalOvertimeHours($employee_id, $start_date, $end_date);

        // Fetch Night Premium Pay records and calculate total night premium pay
        $night_premium_pay = NightPremium::getTotalNightPremiumPay($employee_id, $start_date, $end_date);
        $night_premium_hours = NightPremium::getTotalNightHours($employee_id, $start_date, $end_date);

        // Total deductions include absent, late, undertime deductions, and no attendance deductions
        $total_deductions = $late_deduction + $undertime_deduction; 
        // $absent_deduction + 

        // Deduct Contributions (if within payroll period)
        $contribution_deductions = 0;
        $employer_contributions = [
            'employer_sss_contribution' => 0,
            'employer_philhealth_contribution' => 0,
            'employer_pagibig_contribution' => 0
        ];
        
        if ($contributions) {
            $contribution_deductions += $contributions->sss_contribution ?? 0;
            $contribution_deductions += $contributions->pagibig_contribution ?? 0;
            $contribution_deductions += $contributions->philhealth_contribution ?? 0;
            $contribution_deductions += $contributions->tin_contribution ?? 0;
            
            // Get employer contributions
            $employer_contributions = [
                'employer_sss_contribution' => $contributions->employer_sss_contribution ?? 0,
                'employer_philhealth_contribution' => $contributions->employer_philhealth_contribution ?? 0,
                'employer_pagibig_contribution' => $contributions->employer_pagibig_contribution ?? 0
            ];
        }

        // Deduct Loans (if within payroll period)
        $loan_deductions = 0;
        $sss_loan = 0;
        $pagibig_loan = 0;
        $cash_advance = 0;
        
        if ($loans) {
            // If loans is a collection (for the updated code path)
            if ($loans instanceof \Illuminate\Database\Eloquent\Collection) {
                // Cash advances are split into bi-monthly payments
                // First half: 26th of previous month to 10th of current month
                // Second half: 11th to 25th of current month
                // We collect all loan records within the pay period to ensure we capture all payments
                foreach ($loans as $loan) {
                    $sss_loan += $loan->sss_loan ?? 0;
                    $pagibig_loan += $loan->pagibig_loan ?? 0;
                    $cash_advance += $loan->cash_advance ?? 0;
                    
                    // If this loan includes detailed notes about the covered period, log it for reference
                    if ($loan->notes && str_contains($loan->notes, 'half')) {
                        \Illuminate\Support\Facades\Log::info('Processing cash advance payment for period: ' . $loan->notes);
                    }
                }
            } else {
                // For the old code path (should not happen with updated system)
                $sss_loan = $loans->sss_loan ?? 0;
                $pagibig_loan = $loans->pagibig_loan ?? 0;
                $cash_advance = $loans->cash_advance ?? 0;
            }
            
            $loan_deductions = $sss_loan + $pagibig_loan + $cash_advance;
        }

        // Calculate holiday hours and pay
        $holidays = $this->holidayService->getHolidaysInRange($start, $end);
        $holiday_hours = 0;
        $holiday_pay = 0;
        $regular_holiday_hours = 0;
        $special_holiday_hours = 0;
        $special_working_holiday_hours = 0;
        
        foreach ($holidays as $holiday) {
            // Skip holidays that are on weekends, as they might be counted already
            $holidayDate = Carbon::parse($holiday->date);
            if ($holidayDate->isWeekend()) {
                Log::info("Holiday {$holiday->title} falls on weekend, not counting additional hours");
                continue;
            }
            
            // Track total holiday hours
            $holiday_hours += $holiday->holiday_hours;
            
            // Track holiday hours by type
            if ($holiday->type === Holiday::TYPE_REGULAR) {
                $regular_holiday_hours += $holiday->holiday_hours;
                // Regular holiday pays 100% of daily rate
                $holiday_pay += ($daily_salary * $holiday->holiday_hours / 8);
            } elseif ($holiday->type === Holiday::TYPE_SPECIAL) {
                $special_holiday_hours += $holiday->holiday_hours;
                // Special non-working holiday pays 30% of daily rate
                $holiday_pay += ($daily_salary * $holiday->holiday_hours / 8) * 0.3;
            } elseif ($holiday->type === Holiday::TYPE_SPECIAL_WORKING) {
                $special_working_holiday_hours += $holiday->holiday_hours;
                // Special working holidays don't add pay as they're treated as regular working days
            }
            
            Log::info("Added holiday {$holiday->title} with {$holiday->holiday_hours} hours to payroll for employee {$employee_id}");
        }

        // Get unpaid approved leaves within the payroll period
        $unpaid_leaves = Leave::where('employee_id', $employee_id)
            ->where('status', 'approved')
            ->where('payment_status', 'Without Pay')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('date_from', [$start, $end])
                    ->orWhereBetween('date_to', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('date_from', '<', $start)
                          ->where('date_to', '>', $end);
                    });
            })
            ->get();

        // Sum up the calculated hours from unpaid approved leaves
        $unpaid_leave_hours = 0;
        foreach ($unpaid_leaves as $leave) {
            $unpaid_leave_hours += $leave->calculated_hours;
            
            // Log details of each unpaid leave being included
            Log::info("Including unpaid leave (ID: {$leave->id}) in payroll for employee {$employee_id}", [
                'leave_id' => $leave->id,
                'employee_id' => $employee_id,
                'date_from' => $leave->date_from,
                'date_to' => $leave->date_to,
                'calculated_hours' => $leave->calculated_hours,
                'leave_type' => $leave->leave_type,
                'type_name' => $leave->type->name ?? 'Unknown',
                'payroll_period' => "{$start_date} to {$end_date}"
            ]);
        }

        // Calculate Net Salary
        $net_salary = $gross_salary - $total_deductions - $contribution_deductions - $loan_deductions + $overtime_pay + $night_premium_pay - $absent_deduction;

        // Calculate Total Earnings
        $total_earnings = $this->calculateTotalEarnings($gross_salary, $overtime_pay);

        // Store payroll record
        $payroll = Payroll::create([
            'employee_id' => $employee_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'gross_salary' => $gross_salary,
            'net_salary' => $net_salary,
            'late_deduction' => $late_deduction,
            'undertime_deduction' => $undertime_deduction,
            'absent_deduction' => $absent_deduction,
            'unpaid_leave_hours' => $unpaid_leave_hours,
            'late_time' => $late_time_total,
            'under_time' => $under_time_total,
            'sss_contribution' => $contributions->sss_contribution ?? 0,
            'pagibig_contribution' => $contributions->pagibig_contribution ?? 0,
            'philhealth_contribution' => $contributions->philhealth_contribution ?? 0,
            'tin_contribution' => $contributions->tin_contribution ?? 0,
            'employer_sss_contribution' => $employer_contributions['employer_sss_contribution'],
            'employer_philhealth_contribution' => $employer_contributions['employer_philhealth_contribution'],
            'employer_pagibig_contribution' => $employer_contributions['employer_pagibig_contribution'],
            'sss_loan' => $sss_loan,
            'pagibig_loan' => $pagibig_loan,
            'cash_advance' => $cash_advance,
            'overtime_pay' => $overtime_pay,
            'overtime_hours' => $overtime_hours,
            'night_premium_pay' => $night_premium_pay,
            'night_premium_hours' => $night_premium_hours,
            'total_earnings' => $total_earnings,
            'holiday_hours' => $holiday_hours,
            'holiday_pay' => $holiday_pay,
            'regular_holiday_hours' => $regular_holiday_hours,
            'special_holiday_hours' => $special_holiday_hours,
            'special_working_holiday_hours' => $special_working_holiday_hours,
        ]);

        return $payroll;
    }


    private function calculateTotalEarnings($basic_salary, $overtime_pay)
    {
        return $basic_salary + $overtime_pay;
    }

    private function calculateWorkingDays($employee)
    {
        return $employee->department->name === "BGPDI" ? 7 : 13;
    }
    
    public function deductionPerMinute($employee)
    {
        $daily_salary = $employee->salary / 26;
        $deduction_per_minute = 8 * 60;
        return $daily_salary / $deduction_per_minute;
    }
    
    /**
     * Add two time strings in format HH:MM
     *
     * @param string $time1
     * @param string $time2
     * @return string
     */
    private function addTimes($time1, $time2)
    {
        // Parse times
        [$hours1, $minutes1] = array_map('intval', explode(':', $time1));
        [$hours2, $minutes2] = array_map('intval', explode(':', $time2));
        
        // Add times
        $totalMinutes = ($hours1 * 60 + $minutes1) + ($hours2 * 60 + $minutes2);
        
        // Convert back to HH:MM format
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}