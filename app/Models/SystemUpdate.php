<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'published_at',
        'is_active',
        'author_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
