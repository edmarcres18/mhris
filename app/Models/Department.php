<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Department extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'head_name'
    ];

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    public function position(): HasMany
    {
        return $this->hasMany(Position::class);
    }
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
