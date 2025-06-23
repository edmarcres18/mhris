<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory, Loggable;

    protected const REMARKS = [
        'SUNDAY' => 'Sunday',
        'SATURDAY' => 'Saturday',
        'HOLIDAY' => 'Holiday',
        'ON_LEAVE' => 'On Leave',
        'ABSENT' => 'Absent',
        'LATE' => 'Late',
        'UNDERTIME' => 'UnderTime',
        'PRESENT' => 'Present',
        'NO_CLOCK_OUT' => 'No Clock Out',
        'HALF_DAY' => 'Half Day'
    ];

    public static function getRemarks()
    {
        return self::REMARKS;
    }

    protected $fillable = [
        'employee_id',
        'date_attended',
        'time_in',
        'time_out',
        'time_stamp1',
        'time_stamp2',
        'time_in_address',
        'time_out_address',
        'remarks',
        'hours_worked',
        'leave_payment_status',
        'overtime_hours',
        'late_time',
        'under_time',
        'unpaid_leave_time',
    ];

    protected $casts = [
        'date_attended' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'hours_worked' => 'float',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function calculateRemarksAndHoursWorked()
    {
        $dateAttended = Carbon::parse($this->date_attended);
        $dayOfWeek = $dateAttended->dayOfWeek;
        $isHoliday = Holiday::whereDate('date', $dateAttended)->exists();
        $leave = Leave::where('employee_id', $this->employee_id)
                      ->whereDate('date_from', '<=', $dateAttended)
                      ->whereDate('date_to', '>', $dateAttended)
                      ->where('status', 'approved')
                      ->first();

        // Check for Sunday
        if ($dayOfWeek == Carbon::SUNDAY) {
            $this->setNoWorkDay('Sunday');
            return;
        }

        if ($dayOfWeek == Carbon::SATURDAY) {
            // Get employee's employment status and calculate days since hiring
            $employmentStatus = $this->employee->employment_status ?? null;
            $daysEmployed = Carbon::parse($this->employee->date_hired)->diffInDays(Carbon::now());

            // Check if time_in and time_out already have values (employee actually clocked in/out)
            if ($this->time_in && $this->time_out) {
                $shiftStart = Carbon::parse('08:00:00');
                $shiftEnd = Carbon::parse('17:00:00');
                $timeIn = Carbon::parse($this->time_in);
                $timeOut = Carbon::parse($this->time_out);
                $this->remarks = 'Saturday';
                
                // Check for Late
                if ($timeIn->gt($shiftStart)) {
                    $this->remarks = 'Late';
                    $lateDuration = $timeIn->diffInMinutes($shiftStart);
                    $this->late_time = sprintf('%02d:%02d', floor($lateDuration / 60), $lateDuration % 60);
                    
                    // Calculate hours worked as 8:00:00 minus late time
                    $lateHours = floor($lateDuration / 60);
                    $lateMinutes = $lateDuration % 60;
                    $totalWorkMinutes = (8 * 60) - $lateDuration;
                    $this->hours_worked = sprintf('%02d:%02d:00', floor($totalWorkMinutes / 60), $totalWorkMinutes % 60);
                }
                else {
                    $this->hours_worked = '08:00:00';
                }
                
                $this->leave_payment_status = 'With Pay';
                return;
            }

            // For non-regular employees with 30 or more days of employment
            if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed >= 30) {
                $this->time_in = '08:00:00';
                $this->time_out = '17:00:00';
                $this->remarks = 'Saturday';
                $this->hours_worked = '08:00:00';
                $this->leave_payment_status = 'With Pay';
                return;
            }
            // For non-regular employees with less than 30 days of employment
            else if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed < 30) {
                $this->time_in = null;
                $this->time_out = null;
                $this->remarks = 'Saturday';
                $this->hours_worked = '00:00:00';
                $this->leave_payment_status = 'Without Pay';
                return;
            }

            // For regular employees
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->remarks = 'Saturday';
            $this->hours_worked = '08:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }

        if ($isHoliday) {
            $holiday = Holiday::whereDate('date', $dateAttended)->first();
            if ($holiday->type !== 'Regular Holiday' && $holiday->type !== 'Special Non-Working Holiday') {
                $this->setAbsentForNonRegularHoliday();
                return;
            }
            $this->setHolidayAttendance();
            return;
        }

        if ($leave) {
            $this->setLeaveAttendance($leave);
            return;
        }

        // Regular work day
        $this->setRegularWorkDay();
    }

    private function setNoWorkDay($reason)
    {
        $this->time_in = $this->time_in ?? null;
        $this->time_out = $this->time_out ?? null;
        $this->remarks = $reason;
        $this->hours_worked = $this->hours_worked ?? '00:00:00';
        $this->leave_payment_status = $this->leave_payment_status ?? null;
    }

    private function setHolidayAttendance()
    {
        // Get employee's employment status and calculate days since hiring
        $employmentStatus = $this->employee->employment_status ?? null;
        $daysEmployed = Carbon::parse($this->employee->date_hired)->diffInDays(Carbon::now());

        // For non-regular employees with 30 or more days of employment
        if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed >= 30) {
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->remarks = 'Holiday';
            $this->hours_worked = '08:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }
        // For non-regular employees with less than 30 days of employment
        else if ($employmentStatus !== 'REGULAR EMPLOYEE' && $daysEmployed < 30) {
            $this->time_in = null;
            $this->time_out = null;
            $this->remarks = 'Holiday';
            $this->hours_worked = '00:00:00';
            $this->leave_payment_status = 'Without Pay';
            return;
        }

        // For regular employees (detailed setup)
        $this->time_in = '08:00:00';      // Standard start time
        $this->time_out = '17:00:00';     // Standard end time
        $this->remarks = 'Holiday';        // Mark as holiday
        $this->hours_worked = '08:00:00';  // Full day of work credited
        $this->leave_payment_status = 'With Pay';  // Regular employees always get paid holidays
    }

    private function setLeaveAttendance($leave)
    {
        $leavePaymentStatus = $leave->getLeavePaymentStatus();

        if ($leavePaymentStatus === 'With Pay') {
            $this->time_in = '08:00:00';
            $this->time_out = '17:00:00';
            $this->hours_worked = '08:00:00';
        } else {
            $this->time_in = null;
            $this->time_out = null;
            $this->hours_worked = '00:00:00';
            $this->unpaid_leave_time = '08:00:00'; // Standard work day duration as unpaid
        }

        $this->remarks = 'On Leave';
        $this->leave_payment_status = $leavePaymentStatus;
    }

    private function setRegularWorkDay()
    {
        // Check if employee belongs to BGPDI department and apply special rules if needed
        if ($this->employeeBelongsToBGPDI()) {
            $this->setBGPDIAttendance();
            return;
        }

        $shiftStart = Carbon::parse('08:00:00');
        $shiftEnd = Carbon::parse('17:00:00');
        $halfDayTime = Carbon::parse('12:00:00');

        $timeIn = $this->time_in ? Carbon::parse($this->time_in) : null;
        $timeOut = $this->time_out ? Carbon::parse($this->time_out) : null;

        if (!$timeIn && !$timeOut) {
            $this->setNoWorkDay('Absent');
            return;
        }

        // Check for Half Day condition
        if ($timeIn && $timeOut && 
            $timeIn->format('H:i:s') === '08:00:00' && 
            $timeOut->format('H:i:s') === '12:00:00') {
            $this->remarks = 'Half Day';
            $this->hours_worked = '04:00:00';
            $this->leave_payment_status = 'With Pay';
            return;
        }

        if ($timeIn && $timeIn->gt($shiftStart)) {
            $this->remarks = 'Late';
            $this->late_time = $this->calculateLateTime();
        } elseif ($timeOut && $timeOut->lt($shiftEnd)) {
            $this->remarks = 'UnderTime';
            $this->under_time = $this->calculateUnderTime();
        } else {
            $this->remarks = 'Present';
        }

        if (!$timeOut) {
            $this->remarks = 'No Clock Out';
        }

        $this->hours_worked = $this->getHoursWorkedAttribute();
        $this->leave_payment_status = 'With Pay'; // Assuming regular work days are always with pay
    }

    public function getHoursWorkedAttribute(): string
    {
        if (is_null($this->time_in) || is_null($this->time_out)) {
            return '00:00:00';
        }

        $shiftStart = Carbon::parse('08:00:00');
        $timeIn = Carbon::parse($this->time_in);
        $timeOut = Carbon::parse($this->time_out);

        if ($timeIn->lt($shiftStart)) {
            $timeIn = $shiftStart;
        }

        if ($timeOut->greaterThanOrEqualTo(Carbon::createFromTime(13, 0))) {
            $timeOut = $timeOut->subHours(1);
        }

        $hoursWorked = $timeIn->diff($timeOut);

        return $hoursWorked->format('%H:%I:%S');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            // Store the late time and under time details
            if ($model->remarks === 'Late' && !$model->late_time) {
                $model->late_time = $model->calculateLateTime();
                $model->save();
            }

            if ($model->remarks === 'UnderTime' && !$model->under_time) {
                $model->under_time = $model->calculateUnderTime();
                $model->save();
            }

            if ($model->remarks === 'On Leave' && $model->leave_payment_status === 'Without Pay' && !$model->unpaid_leave_time) {
                $model->unpaid_leave_time = '08:00:00'; // Standard workday duration
                $model->save();
            }
        });

        static::saving(function ($model) {
            $model->calculateRemarksAndHoursWorked();
        });
    }

    private function calculateHoursWorked($timeIn, $timeOut)
    {
        if ($timeIn && $timeOut) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $timeIn);
            $end = \Carbon\Carbon::createFromFormat('H:i', $timeOut);
            return $end->diffInHours($start);
        }
        return null;
    }

    public function getAttendancePoints(): float
    {
        // Assuming 'PRESENT' status gets 0.5 points
        if ($this->remarks === 'Present' && $this->leave_payment_status === 'With Pay') {
            return 0.5;
        }
        return 0;
    }

    private function setAbsentForNonRegularHoliday()
    {
        $this->time_in = null;
        $this->time_out = null;
        $this->remarks = 'Absent';
        $this->hours_worked = '00:00:00';
        $this->leave_payment_status = null;
    }

    public function calculateLateTime(): string
    {
        if ($this->remarks === 'Late' && $this->time_in) {
            $shiftStart = Carbon::parse('08:00:00');
            $timeIn = Carbon::parse($this->time_in);

            // Calculate the difference in minutes
            $lateDuration = $timeIn->diffInMinutes($shiftStart);

            // Format the late duration as HH:MM
            return sprintf('%02d:%02d', floor($lateDuration / 60), $lateDuration % 60);
        }

        return '00:00'; // No late time if not late
    }

    public function calculateUnderTime(): string
    {
        if ($this->remarks === 'UnderTime' && $this->time_out) {
            $shiftEnd = Carbon::parse('17:00:00');
            $timeOut = Carbon::parse($this->time_out);

            // Calculate the difference in minutes
            $underTimeDuration = $shiftEnd->diffInMinutes($timeOut);

            // Format the under time duration as HH:MM
            return sprintf('%02d:%02d', floor($underTimeDuration / 60), $underTimeDuration % 60);
        }

        return '00:00'; // No under time if not undertime
    }

    public function getUnpaidLeaveTimeAttribute(): string
    {
        if ($this->remarks === 'On Leave' && $this->leave_payment_status === 'Without Pay') {
            return $this->unpaid_leave_time ?? '08:00:00'; // Default to standard workday
        }

        return '00:00'; // No unpaid leave time if not on unpaid leave
    }

    /**
     * Check if employee belongs to BGPDI department
     */
    private function employeeBelongsToBGPDI(): bool
    {
        return $this->employee && $this->employee->department && 
               $this->employee->department->name === 'BGPDI';
    }

    /**
     * Set attendance rules for BGPDI department employees
     */
    private function setBGPDIAttendance()
    {
        $positionName = $this->employee->position->name ?? '';
        $timeIn = $this->time_in ? Carbon::parse($this->time_in) : null;
        $timeOut = $this->time_out ? Carbon::parse($this->time_out) : null;

        if (!$timeIn && !$timeOut) {
            $this->setNoWorkDay('Absent');
            return;
        }

        if ($positionName === 'MERCHANDISER') {
            $this->handleMerchandiserAttendance($timeIn, $timeOut);
        } elseif (in_array($positionName, ['CASHIER', 'PUMP ATTENDANT'])) {
            $this->handleCashierAndPumpAttendantAttendance($timeIn, $timeOut);
        } else {
            // For other positions in BGPDI, use standard rules
            $this->handleStandardAttendance($timeIn, $timeOut);
        }

        $this->hours_worked = $this->getHoursWorkedAttribute();
        $this->leave_payment_status = 'With Pay';
    }

    /**
     * Handle attendance rules for Merchandiser position
     */
    private function handleMerchandiserAttendance($timeIn, $timeOut)
    {
        $shiftStart = Carbon::parse('10:00:00');
        $shiftEnd = Carbon::parse('19:00:00');

        if (!$timeOut) {
            $this->remarks = 'No Clock Out';
            return;
        }

        if ($timeIn && $timeIn->gt($shiftStart)) {
            $this->remarks = 'Late';
            $this->late_time = $this->calculateCustomLateTime($timeIn, $shiftStart);
        } elseif ($timeOut && $timeOut->lt($shiftEnd)) {
            $this->remarks = 'UnderTime';
            $this->under_time = $this->calculateCustomUnderTime($timeOut, $shiftEnd);
        } else {
            $this->remarks = 'Present';
        }
    }

    /**
     * Handle attendance rules for Cashier and Pump Attendant positions
     */
    private function handleCashierAndPumpAttendantAttendance($timeIn, $timeOut)
    {
        if (!$timeIn || !$timeOut) {
            if (!$timeOut) {
                $this->remarks = 'No Clock Out';
            } else {
                $this->remarks = 'Absent';
            }
            return;
        }

        // Determine which shift the employee is working
        $morningShift = $timeIn->hour < 12;
        
        if ($morningShift) {
            $shiftStart = Carbon::parse('05:00:00');
            $shiftEnd = Carbon::parse('13:00:00');
        } else {
            $shiftStart = Carbon::parse('13:00:00');
            $shiftEnd = Carbon::parse('21:00:00');
        }

        if ($timeIn->gt($shiftStart)) {
            $this->remarks = 'Late';
            $this->late_time = $this->calculateCustomLateTime($timeIn, $shiftStart);
        } elseif ($timeOut->lt($shiftEnd)) {
            $this->remarks = 'UnderTime';
            $this->under_time = $this->calculateCustomUnderTime($timeOut, $shiftEnd);
        } else {
            $this->remarks = 'Present';
        }
    }

    /**
     * Handle attendance with standard rules
     */
    private function handleStandardAttendance($timeIn, $timeOut)
    {
        $shiftStart = Carbon::parse('08:00:00');
        $shiftEnd = Carbon::parse('17:00:00');

        if (!$timeOut) {
            $this->remarks = 'No Clock Out';
            return;
        }

        if ($timeIn && $timeIn->gt($shiftStart)) {
            $this->remarks = 'Late';
            $this->late_time = $this->calculateCustomLateTime($timeIn, $shiftStart);
        } elseif ($timeOut && $timeOut->lt($shiftEnd)) {
            $this->remarks = 'UnderTime';
            $this->under_time = $this->calculateCustomUnderTime($timeOut, $shiftEnd);
        } else {
            $this->remarks = 'Present';
        }
    }

    /**
     * Calculate late time for custom shift schedules
     */
    private function calculateCustomLateTime($timeIn, $shiftStart): string
    {
        $lateDuration = $timeIn->diffInMinutes($shiftStart);
        return sprintf('%02d:%02d', floor($lateDuration / 60), $lateDuration % 60);
    }

    /**
     * Calculate under time for custom shift schedules
     */
    private function calculateCustomUnderTime($timeOut, $shiftEnd): string
    {
        $underTimeDuration = $shiftEnd->diffInMinutes($timeOut);
        return sprintf('%02d:%02d', floor($underTimeDuration / 60), $underTimeDuration % 60);
    }
}
