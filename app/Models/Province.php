<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Province extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = [
        'name'
    ];

    public function city(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
