<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class OvertimePay extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'overtime_hours',
        'overtime_rate',
        'overtime_pay',
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
        'overtime_hours' => 0,
        'overtime_pay' => 0,
        'approval_status' => 'pending',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who approved this overtime pay
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
     * Calculate overtime hours based on time_in and time_out
     *
     * @return float|null
     */
    public function calculateOvertimeHours(): ?float
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

        // Update the overtime_hours field
        $this->overtime_hours = $hours;
        $this->save();

        return $hours;
    }

    /**
     * Calculate the overtime pay based on overtime hours and overtime rate.
     *
     * @return float
     */
    public function calculateOvertimePay(): float
    {
        // If overtime_hours is not set or is zero, try to calculate hours first
        if ((!$this->overtime_hours || $this->overtime_hours <= 0) && $this->time_in && $this->time_out) {
            try {
                $this->calculateOvertimeHours();
            } catch (\Exception $e) {
                // If calculation fails, log or handle exception
                \Illuminate\Support\Facades\Log::error('Failed to calculate overtime hours: ' . $e->getMessage());
            }
        }

        // If still no overtime_hours, return 0
        if (!$this->overtime_hours || $this->overtime_hours <= 0) {
            $this->overtime_pay = 0;
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
            
            // Apply overtime rate multiplier (e.g., 1.25 for regular overtime, 1.5 for holiday overtime)
            $overtimeRate = $this->overtime_rate ?? 1.25; // Default to 1.25 if not specified
            
            // Ensure overtime rate is valid
            if (!is_numeric($overtimeRate) || $overtimeRate < 1) {
                $overtimeRate = 1.25; // Use default if invalid
            }
            
            // Calculate final overtime pay
            $overtimePay = $hourlyRate * $this->overtime_hours * $overtimeRate;

            // Handle potential calculation errors
            if (!is_numeric($overtimePay) || $overtimePay < 0) {
                $overtimePay = 0;
            }

            // Update the overtime_pay field with rounded value to 2 decimal places
            $this->overtime_pay = round($overtimePay, 2);
            $this->save();

            return $this->overtime_pay;
        } catch (\Exception $e) {
            // Log the error and return 0
            \Illuminate\Support\Facades\Log::error('Error calculating overtime pay: ' . $e->getMessage());
            $this->overtime_pay = 0;
            $this->save();
            return 0;
        }
    }

    /**
     * Determine current approval level needed based on employee rank and current status
     * 
     * @return string Current approval level ('supervisor', 'finance_head', 'vp_finance', or 'completed')
     */
    public function determineApprovalLevel(): string
    {
        // Fetch the employee to check rank
        $employee = $this->employee;
        
        if (!$employee) {
            Log::error('Employee not found for overtime ID: ' . $this->id);
            return 'error';
        }
        
        // Check if already fully approved or rejected
        if ($this->approval_status === 'approved' || $this->approval_status === 'rejected') {
            return 'completed';
        }
        
        // For managerial employees, skip supervisor approval
        if ($employee->rank === 'Managerial') {
            // If finance head hasn't approved yet
            if ($this->finance_head_approval_status === 'pending') {
                return 'finance_head';
            }
            // If finance head approved but VP finance hasn't
            elseif ($this->finance_head_approval_status === 'approved' && 
                $this->vp_finance_approval_status === 'pending') {
                return 'vp_finance';
            }
        } 
        // For rank and file employees
        else {
            // If supervisor hasn't approved yet
            if ($this->supervisor_approval_status === 'pending') {
                return 'supervisor';
            }
            // If supervisor rejected, process is ended
            elseif ($this->supervisor_approval_status === 'rejected') {
                return 'completed';
            }
            // If supervisor approved but finance head hasn't
            elseif ($this->supervisor_approval_status === 'approved' && 
                $this->finance_head_approval_status === 'pending') {
                return 'finance_head';
            }
            // If finance head rejected, process is ended
            elseif ($this->finance_head_approval_status === 'rejected') {
                return 'completed';
            }
            // If finance head approved but VP finance hasn't
            elseif ($this->finance_head_approval_status === 'approved' && 
                $this->vp_finance_approval_status === 'pending') {
                return 'vp_finance';
            }
        }
        
        // If all approvals are complete, the process is finished
        return 'completed';
    }

    /**
     * Update the current approval level after each approval/rejection
     * 
     * @return void
     */
    public function updateApprovalLevel(): void
    {
        $this->current_approval_level = $this->determineApprovalLevel();
        $this->save();
    }

    /**
     * Check if the current authenticated user can approve at the current level
     * 
     * @return bool
     */
    public function canBeApprovedByCurrentUser(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        $currentLevel = $this->determineApprovalLevel();
        
        // If already completed, no further approvals needed
        if ($currentLevel === 'completed') {
            return false;
        }
        
        // For supervisor level
        if ($currentLevel === 'supervisor') {
            // User must have Supervisor role and be in the same department
            if ($user->hasRole('Supervisor') && 
                $user->department_id === $this->employee->department_id) {
                return true;
            }
        }
        // For finance head level
        elseif ($currentLevel === 'finance_head') {
            if ($user->hasRole('Finance Head')) {
                return true;
            }
        }
        // For VP finance level
        elseif ($currentLevel === 'vp_finance') {
            if ($user->hasRole('VP Finance')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Approve by supervisor
     * 
     * @param int $userId The ID of the supervisor who is approving
     * @return bool
     */
    public function supervisorApprove(int $userId): bool
    {
        if ($this->determineApprovalLevel() !== 'supervisor') {
            Log::error('Cannot approve at supervisor level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->supervisor_approval_status = 'approved';
        $this->supervisor_approved_by = $userId;
        $this->supervisor_approved_at = now();
        $this->updateApprovalLevel();
        
        return $this->save();
    }
    
    /**
     * Reject by supervisor
     * 
     * @param int $userId The ID of the supervisor who is rejecting
     * @param string $reason The reason for rejection
     * @return bool
     */
    public function supervisorReject(int $userId, string $reason = ''): bool
    {
        if ($this->determineApprovalLevel() !== 'supervisor') {
            Log::error('Cannot reject at supervisor level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->supervisor_approval_status = 'rejected';
        $this->supervisor_approved_by = $userId;
        $this->supervisor_approved_at = now();
        $this->rejection_reason = $reason;
        
        // Also update the main approval status
        $this->approval_status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        
        $this->updateApprovalLevel();
        
        return $this->save();
    }
    
    /**
     * Approve by finance head
     * 
     * @param int $userId The ID of the finance head who is approving
     * @return bool
     */
    public function financeHeadApprove(int $userId): bool
    {
        if ($this->determineApprovalLevel() !== 'finance_head') {
            Log::error('Cannot approve at finance head level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->finance_head_approval_status = 'approved';
        $this->finance_head_approved_by = $userId;
        $this->finance_head_approved_at = now();
        $this->updateApprovalLevel();
        
        return $this->save();
    }
    
    /**
     * Reject by finance head
     * 
     * @param int $userId The ID of the finance head who is rejecting
     * @param string $reason The reason for rejection
     * @return bool
     */
    public function financeHeadReject(int $userId, string $reason = ''): bool
    {
        if ($this->determineApprovalLevel() !== 'finance_head') {
            Log::error('Cannot reject at finance head level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->finance_head_approval_status = 'rejected';
        $this->finance_head_approved_by = $userId;
        $this->finance_head_approved_at = now();
        $this->rejection_reason = $reason;
        
        // Also update the main approval status
        $this->approval_status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        
        $this->updateApprovalLevel();
        
        return $this->save();
    }
    
    /**
     * Approve by VP finance
     * 
     * @param int $userId The ID of the VP finance who is approving
     * @return bool
     */
    public function vpFinanceApprove(int $userId): bool
    {
        if ($this->determineApprovalLevel() !== 'vp_finance') {
            Log::error('Cannot approve at VP finance level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->vp_finance_approval_status = 'approved';
        $this->vp_finance_approved_by = $userId;
        $this->vp_finance_approved_at = now();
        
        // Also update the main approval status
        $this->approval_status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        
        $this->updateApprovalLevel();
        
        return $this->save();
    }
    
    /**
     * Reject by VP finance
     * 
     * @param int $userId The ID of the VP finance who is rejecting
     * @param string $reason The reason for rejection
     * @return bool
     */
    public function vpFinanceReject(int $userId, string $reason = ''): bool
    {
        if ($this->determineApprovalLevel() !== 'vp_finance') {
            Log::error('Cannot reject at VP finance level, current level is: ' . $this->determineApprovalLevel());
            return false;
        }
        
        $this->vp_finance_approval_status = 'rejected';
        $this->vp_finance_approved_by = $userId;
        $this->vp_finance_approved_at = now();
        $this->rejection_reason = $reason;
        
        // Also update the main approval status
        $this->approval_status = 'rejected';
        $this->approved_by = $userId;
        $this->approved_at = now();
        
        $this->updateApprovalLevel();
        
        return $this->save();
    }

    /**
     * Get approval status details for display
     * 
     * @return array
     */
    public function getApprovalStatusDetails(): array
    {
        $employee = $this->employee;
        $isManagerial = $employee && $employee->rank === 'Managerial';
        
        $details = [
            'current_level' => $this->current_approval_level,
            'overall_status' => $this->approval_status,
            'is_managerial' => $isManagerial,
        ];
        
        // For managerial employees, we only include finance head and VP finance approvals
        if ($isManagerial) {
            $details['approvals'] = [
                'finance_head' => [
                    'status' => $this->finance_head_approval_status,
                    'approved_by' => $this->financeHeadApprover ? $this->financeHeadApprover->name : null,
                    'approved_at' => $this->finance_head_approved_at,
                ],
                'vp_finance' => [
                    'status' => $this->vp_finance_approval_status,
                    'approved_by' => $this->vpFinanceApprover ? $this->vpFinanceApprover->name : null,
                    'approved_at' => $this->vp_finance_approved_at,
                ]
            ];
        } 
        // For rank and file employees, we include all three approval levels
        else {
            $details['approvals'] = [
                'supervisor' => [
                    'status' => $this->supervisor_approval_status,
                    'approved_by' => $this->supervisorApprover ? $this->supervisorApprover->name : null,
                    'approved_at' => $this->supervisor_approved_at,
                ],
                'finance_head' => [
                    'status' => $this->finance_head_approval_status,
                    'approved_by' => $this->financeHeadApprover ? $this->financeHeadApprover->name : null,
                    'approved_at' => $this->finance_head_approved_at,
                ],
                'vp_finance' => [
                    'status' => $this->vp_finance_approval_status,
                    'approved_by' => $this->vpFinanceApprover ? $this->vpFinanceApprover->name : null,
                    'approved_at' => $this->vp_finance_approved_at,
                ]
            ];
        }
        
        $details['rejection_reason'] = $this->rejection_reason;
        
        return $details;
    }
    
    /**
     * Get the next approver role name based on current approval level
     * 
     * @return string
     */
    public function getNextApproverRoleName(): string
    {
        $currentLevel = $this->determineApprovalLevel();
        
        switch ($currentLevel) {
            case 'supervisor':
                return 'Supervisor';
            case 'finance_head':
                return 'Finance Head';
            case 'vp_finance':
                return 'VP Finance';
            default:
                return 'No approver needed';
        }
    }


    /**
     * Get total overtime hours for a specific employee within a date range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getTotalOvertimeHours(int $employeeId, string $startDate, string $endDate): float
    {
        return self::where('employee_id', $employeeId)
            ->whereBetween('approved_at_vpfinance', [$startDate, $endDate])
            ->where('approval_status', 'approvedByVPFinance')
            ->sum('overtime_hours');
    }

    /**
     * Get total overtime pay for a specific employee within a date range
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getTotalOvertimePay(int $employeeId, string $startDate, string $endDate): float
    {
        return self::where('employee_id', $employeeId)
            ->whereBetween('approved_at_vpfinance', [$startDate, $endDate])
            ->where('approval_status', 'approvedByVPFinance')
            ->sum('overtime_pay');
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
}