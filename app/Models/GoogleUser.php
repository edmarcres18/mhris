<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class GoogleUser extends Authenticatable
{
    use HasFactory, Notifiable, Loggable;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'token',
    ];

    protected $casts = [
        'token' => 'array',
    ];

    // Add these methods to comply with the Authenticatable interface
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return null; // Google users don't have a password
    }

    public function getRememberToken()
    {
        return null; // Implement if you want to use "remember me" functionality
    }

    public function setRememberToken($value)
    {
        // Implement if you want to use "remember me" functionality
    }

    public function getRememberTokenName()
    {
        return null; // Implement if you want to use "remember me" functionality
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }
}
