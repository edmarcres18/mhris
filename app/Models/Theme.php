<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'overlay_type', // video, image, or css
        'overlay_content', // URL for video/image or CSS content
        'overlay_opacity',
        'z_index',
        'additional_css',
        'additional_js'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }
} 