<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveRequestNotification;

class Leave extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'employee_id',
        'date_from',
        'date_to',
        'leave_type',
        'type_id',
        'reason_to_leave',
        'approved_by',
        'status',
        'payment_status',
        'signature',
        'approved_by_signature',
        'validated_by_signature',
        'rejected_by',
        'calculated_hours',
    ];

    protected $primaryKey = 'id'; // This is already the default, but just to be explicit

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_from' => 'datetime',
        'date_to' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedByUser()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function getDiffhoursAttribute()
    {
        $dateFrom = Carbon::parse($this->date_from);
        $dateTo = Carbon::parse($this->date_to);
        $diffInMinutes = $dateFrom->diffInMinutes($dateTo); // Get difference in minutes

        // Calculate hours and minutes
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;

        return compact('hours', 'minutes');
    }

    public function updateLeaveType()
    {
        $employee = $this->employee;
        $diffHours = $this->diffhours;

        if ($this->status === 'approved') {
            $totalHours = $diffHours['hours'] + ($diffHours['minutes'] / 60); // Convert minutes to fraction of hours

            switch ($this->type->name) {
                case 'Sick Leave':
                    $employee->sick_leave -= $totalHours;
                    break;
                case 'Emergency Leave':
                    $employee->emergency_leave -= $totalHours;
                    break;
                case 'Vacation Leave':
                    $employee->vacation_leave -= $totalHours;
                    break;
                // Add cases for other leave types if needed
            }
            $employee->save();
        }
    }

    public function getLeavePaymentStatus()
    {
        // First check if employee is REGULAR
        if ($this->employee->employment_status !== 'REGULAR') {
            return 'Without Pay';
        }

        // Get the total hours for this leave request
        $diffHours = $this->diffhours;
        $requestedHours = $diffHours['hours'] + ($diffHours['minutes'] / 60);

        // Check available leave balance based on leave type
        $availableHours = match ($this->type->name) {
            'Sick Leave' => $this->employee->sick_leave,
            'Vacation Leave' => $this->employee->vacation_leave,
            'Emergency Leave' => $this->employee->emergency_leave,
            default => 0
        };

        // If requested hours are within or equal to available balance
        return $requestedHours <= $availableHours ? 'With Pay' : 'Without Pay';
    }

    public function getDiffdaysAttribute()
    {
        return Carbon::parse($this->date_from)->diffInDays(Carbon::parse($this->date_to));
    }

    /**
     * Scope for global search functionality
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('status', 'LIKE', "%{$searchTerm}%")
              ->orWhere('reason_to_leave', 'LIKE', "%{$searchTerm}%")
              ->orWhere('payment_status', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('employee', function ($q) use ($searchTerm) {
                  $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('company_id', 'LIKE', "%{$searchTerm}%");
              })
              ->orWhereHas('type', function ($q) use ($searchTerm) {
                  $q->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    /**
     * Get formatted date range for display
     */
    public function getDateRangeAttribute()
    {
        $from = Carbon::parse($this->date_from)->format('M d, Y');
        $to = Carbon::parse($this->date_to)->format('M d, Y');
        return "{$from} - {$to}";
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Calculate leave hours based on leave type
     * For 'Leave' type: Each day is 8 hours, and date ranges like 4/3 to 4/4 count as 1 day
     * For other types: Calculate actual hours difference
     */
    public function calculateLeaveHours()
    {
        // If leave type is "Leave", calculate days and convert to hours (8 hours per day)
        if ($this->leave_type === 'Leave') {
            // Get the date difference excluding the end date (so 4/3 to 4/4 is 1 day)
            $dateFrom = Carbon::parse($this->date_from)->startOfDay();
            $dateTo = Carbon::parse($this->date_to)->startOfDay();
            $days = $dateTo->diffInDays($dateFrom);
            
            // Count as at least 1 day if same day or 1 day apart
            $days = max(1, $days);
            
            // Each day equals 8 hours
            $this->calculated_hours = $days * 8;
        } else {
            // For other leave types, calculate actual hours difference
            $diffHours = $this->diffhours;
            $this->calculated_hours = $diffHours['hours'] + ($diffHours['minutes'] / 60);
        }
        
        return $this->calculated_hours;
    }
    
    /**
     * Boot method to automatically calculate hours when saving
     */
    protected static function booted()
    {
        // Calculate hours before saving
        static::saving(function ($leave) {
            $leave->calculateLeaveHours();
        });
    }
}
