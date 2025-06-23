<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory, Notifiable, Loggable;

    protected $fillable = [
        'company_id',
        'profile',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email_address',
        'contact_no',
        'birth_date',
        'birth_place_province',
        'birth_place_city',
        'birth_place_barangay',
        'province_id',
        'city_id',
        'barangay_id',
        'gender_id',
        'position_id',
        'department_id',
        'zip_code',
        'date_hired',
        'salary',
        'sss_no',
        'pagibig_no',
        'tin_no',
        'philhealth_no',
        'elementary',
        'secondary',
        'tertiary',
        'emergency_name',
        'emergency_no',
        'employment_status',
        'employee_status',
        'signature',
        'profile_updated_at',
        'rank',
        'resigned_date',
        'terminated_date',
    ];

    protected $dates = [
        'profile_updated_at',
        'resigned_date',
        'terminated_date',
    ];

    public function saveEmploymentStatus()
    {
        $this->employment_status = $this->employmentStatus();
        $this->save();
    }
/**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email_address;
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
    public function overtime()
    {
        return $this->hasMany(OvertimePay::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function leave(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
    public function type(): HasMany
    {
        return $this->hasMany(Type::class);
    }
    public function accountability(): HasMany
    {
        return $this->hasMany(Accountability::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function credential(): HasMany
    {
        return $this->hasMany(Credentials::class);
    }

    public function sss()
    {
        return $this->hasMany(Sss::class);
    }
    public function pagibig()
    {
        return $this->hasMany(Pagibig::class);
    }

    public function philhealth()
    {
        return $this->hasMany(Philhealth::class);
    }

    public function sssContribution()
    {
        return $this->hasMany(SssContribution::class);
    }
    public function pagibigContribtion()
    {
        return $this->hasMany(PagibigContribution::class);
    }

    public function philhealthContribution()
    {
        return $this->hasMany(PhilhealthContribution::class);
    }
/**
     * Get the Pagibig loans for the employee.
     */
    public function cashAdvance()
    {
        return $this->hasMany(CashAdvance::class);
    }
    public function employmentStatus(): string
    {
        $hiredDate = \Carbon\Carbon::parse($this->date_hired);
        $now = \Carbon\Carbon::now();
        $diffInDays = $hiredDate->diffInDays($now);
        $diffInMonths = $hiredDate->diffInMonths($now);

        // Check if the employee belongs to the MHRHCI department
        if ($this->position && $this->position->name === 'PHSS') {
            if ($diffInDays <= 18) {
                return 'TRAINEE';
            } elseif ($diffInDays >= 19 && $diffInMonths < 4) {
                return 'PROBITIONARY';
            } else {
                return 'REGULAR EMPLOYEE';
            }
        }

        // Default employment status for other departments
        if ($diffInMonths >= 0 && $diffInMonths < 5) {
            return 'PROBITIONARY';
        } else {
            return 'REGULAR';
        }
    }

    public function dailySalary()
    {
        // Assuming the monthly salary is stored in 'salary' field
        return $this->salary / 26;
    }

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a unique slug when creating an employee
        static::creating(function ($employee) {
            // Capitalize first and last names
            $firstName = ucwords($employee->first_name);
            $lastName = ucwords($employee->last_name);
            $middleName = ucwords($employee->middle_name ?? ''); // Handle nullable middle name
            $suffix = ucwords($employee->suffix ?? ''); // Handle nullable suffix

            // Combine company_id, last_name, first_name, middle_name, suffix, and random string
            $employee->slug = $employee->company_id . '-' . Str::slug($lastName . '-' . $firstName . '-' . $middleName . '-' . $suffix) . '-' . Str::random(6);
        });
    }

    public function cashAdvances()
    {
        return $this->hasMany(CashAdvance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
