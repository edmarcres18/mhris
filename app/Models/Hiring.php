<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Hiring extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'position',
        'description',
        'requirements',
        'benefits',
        'location',
        'slug',
        'department_id',
        'employment_type',
        'responsibilities',
    ];

    public function career()
    {
        return $this->hasMany(Career::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($hiring) {
            $hiring->slug = Hiring::generateUniqueSlug($hiring->position);
        });

        static::updating(function ($hiring) {
            if ($hiring->isDirty('position')) {
                $hiring->slug = Hiring::generateUniqueSlug($hiring->position);
            }
        });
    }

    private static function generateUniqueSlug($position)
    {
        $slug = Str::slug($position);
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
