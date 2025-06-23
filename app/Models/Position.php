<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;


class Position extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = [
        'department_id',
        'name',
    ];

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
