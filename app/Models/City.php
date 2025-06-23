<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class City extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = [
        'name',
        'province_id',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function barangay(): HasMany
    {
        return $this->hasMany(Barangay::class);
    }
    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

}
