<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_history';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at',
        'login_successful'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'login_successful' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 