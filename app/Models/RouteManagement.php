<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteManagement extends Model
{
    use HasFactory;

    protected $table = 'route_management';

    protected $fillable = [
        'route_name',
        'route_path',
        'method',
        'controller',
        'action',
        'middleware',
        'type',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeWebRoutes($query)
    {
        return $query->where('type', 'web');
    }

    public function scopeApiRoutes($query)
    {
        return $query->where('type', 'api');
    }
}
