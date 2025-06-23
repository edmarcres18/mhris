<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Type extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = [
        'name',
    ];

    public function leave(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

}
