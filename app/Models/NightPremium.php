<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class NightPremium extends Model
{
    use HasFactory, Loggable;

    protected $table = 'night_premiums';

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'night_hours',
        'night_rate',
        'night_premium_pay',
        'approval_status',
        'approved_by_supervisor',
        'approved_at_supervisor',
        'approved_by_finance',
        'approved_at_finance',
        'approved_by_vpfinance',
        'approved_at_vpfinance',
        'is_read_by_vpfinance',
        'is_read_at_vpfinance',
        'is_read_by_supervisor',
        'is_read_at_supervisor',
        'is_read_by_finance',
        'is_read_at_finance',
        'is_read_by_employee',
        'is_read_at_employee',
        'rejected_by',
        'rejected_at',
        'is_read_by_rejected',
        'is_read_at_rejected',
        'rejection_reason',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'approved_at_supervisor' => 'datetime',
        'approved_at_financehead' => 'datetime',
        'approved_at_vpfinance' => 'datetime',
        'is_read_at_vpfinance' => 'datetime',
        'is_read_at_supervisor' => 'datetime',
        'is_read_at_financehead' => 'datetime',
        'is_read_at_employee' => 'datetime',
        'read_at' => 'datetime',
        'view_at' => 'datetime',
        'rejected_at' => 'datetime',
        'is_read_at_rejected' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'night_hours' => 0,
        'night_premium_pay' => 0,
        'approval_status' => 'pending',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who approved this night premium pay
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the supervisor who approved this night premium
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_supervisor');
    }

    /**
     * Get the finance head who approved this night premium
     */
    public function financeHead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_finance');
    }

    /**
     * Get the VP finance who approved this night premium
     */
    public function vpFinance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_vpfinance');
    }

    /**
     * Get the user who rejected this night premium
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Calculate night hours based on time_in and time_out
     *
     * @return float|null
     */
    public function calculateNightHours(): ?float
    {
        // Check if both time_in and time_out are set
        if (!$this->time_in || !$this->time_out) {
            return null;
        }

        // Ensure time_in is not after time_out
        if ($this->time_in->greaterThan($this->time_out)) {
            throw new \Exception('Time in cannot be after time out');
        }

        // Ensure time_in date matches the date field
        if ($this->time_in->format('Y-m-d') !== $this->date->format('Y-m-d')) {
            throw new \Exception('Time in date must match the overtime date');
        }

        // Calculate the time difference in hours
        $timeDiff = $this->time_out->diffInSeconds($this->time_in);
        $hours = $timeDiff / 3600; // Convert seconds to hours

        // Ensure hours is positive
        if ($hours <= 0) {
            return 0;
        }

        // Round to 2 decimal places
        $hours = round($hours, 2);

        // Update the night_hours field
        $this->night_hours = $hours;
        $this->save();

        return $hours;
    }

    /**
     * Calculate the night premium pay based on night hours and night rate.
     *
     * @return float
     */
    public function calculateNightPremiumPay(): float
    {
        // If night_hours is not set or is zero, try to calculate hours first
        if ((!$this->night_hours || $this->night_hours <= 0) && $this->time_in && $this->time_out) {
            try {
                $this->calculateNightHours();
            } catch (\Exception $e) {
                // If calculation fails, log or handle exception
                \Illuminate\Support\Facades\Log::error('Failed to calculate night hours: ' . $e->getMessage());
            }
        }

        // If still no overtime_hours, return 0
        if (!$this->night_hours || $this->night_hours <= 0) {
            $this->night_premium_pay = 0;
            $this->save();
            return 0;
        }

        try {
            // Retrieve the employee's salary based on the employee_id from the current instance
            $employee = Employee::find($this->employee_id);

            if (!$employee) {
                throw new \Exception('Employee not found');
            }

            if (!is_numeric($employee->salary) || $employee->salary <= 0) {
                throw new \Exception('Invalid employee salary');
            }

            // Calculate daily salary based on monthly salary divided by average working days per month
            $dailySalary = $employee->salary / 26;
            
            // Calculate hourly rate based on 8-hour work day
            $hourlyRate = $dailySalary / 8;
            
            // Apply overtime rate multiplier (e.g., 1.10 for regular overtime, 1.5 for holiday overtime)
            $nightRate = $this->night_rate ?? 1.10; // Default to 1.25 if not specified
            
            // Ensure overtime rate is valid
            if (!is_numeric($nightRate) || $nightRate < 1) {
                $nightRate = 1.10; // Use default if invalid
            }
            
            // Calculate final overtime pay
            $nightPremiumPay = $hourlyRate * $this->night_hours * $nightRate;

            // Handle potential calculation errors
            if (!is_numeric($nightPremiumPay) || $nightPremiumPay < 0) {
                $nightPremiumPay = 0;
            }

            // Update the overtime_pay field with rounded value to 2 decimal places
            $this->night_premium_pay = round($nightPremiumPay, 2);
            $this->save();

            return $this->night_premium_pay;
        } catch (\Exception $e) {
            // Log the error and return 0
            \Illuminate\Support\Facades\Log::error('Error calculating night premium pay: ' . $e->getMessage());
            $this->night_premium_pay = 0;
            $this->save();
            return 0;
        }
    }

    /**
     * Approve the night premium pay record
     *
     * @param int $userId The ID of the user who is approving the night premium
     * @return bool
     */
    public function approve(int $userId): bool
    {
        $this->approval_status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Reject the night premium pay record
     *
     * @param int $userId The ID of the user who is rejecting the night premium
     * @return bool
     */
    public function reject(int $userId): bool
    {
        $this->approval_status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Approve the night premium by supervisor
     *
     * @param int $userId The ID of the supervisor who is approving
     * @return bool
     */
    public function approveBySupervisor(int $userId): bool
    {
        $this->approved_by_supervisor = $userId;
        $this->approved_at_supervisor = now();
        return $this->save();
    }

    /**
     * Approve the night premium by finance head
     *
     * @param int $userId The ID of the finance head who is approving
     * @return bool
     */
    public function approveByFinanceHead(int $userId): bool
    {
        $this->approved_by_finance = $userId;
        $this->approved_at_finance = now();
        return $this->save();
    }

    /**
     * Approve the night premium by VP finance
     *
     * @param int $userId The ID of the VP finance who is approving
     * @return bool
     */
    public function approveByVpFinance(int $userId): bool
    {
        $this->approved_by_vpfinance = $userId;
        $this->approved_at_vpfinance = now();
        return $this->save();
    }

    /**
     * Mark as read by VP finance
     *
     * @return bool
     */
    public function markAsReadByVpFinance(): bool
    {
        $this->is_read_by_vpfinance = true;
        $this->is_read_at_vpfinance = now();
        return $this->save();
    }

    /**
     * Get total night hours for a specific employee within a date range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getTotalNightHours(int $employeeId, string $startDate, string $endDate): float
    {
        return self::where('employee_id', $employeeId)
            ->whereBetween('approved_at_vpfinance', [$startDate, $endDate])
            ->where('approval_status', 'approvedByVPFinance')
            ->sum('night_hours');
    }

    /**
     * Get total night premium pay for a specific employee within a date range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getTotalNightPremiumPay(int $employeeId, string $startDate, string $endDate): float
    {
        return self::where('employee_id', $employeeId)
            ->whereBetween('approved_at_vpfinance', [$startDate, $endDate])
            ->where('approval_status', 'approvedByVPFinance')
            ->sum('night_premium_pay');
    }
}