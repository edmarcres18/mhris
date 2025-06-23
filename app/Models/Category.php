<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Category extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'logo',
        'description'
    ];

    public function products()
    {
        return $this->hasMany(MedicalProduct::class);
    }
} 
