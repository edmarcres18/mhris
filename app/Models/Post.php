<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Post extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = ['user_id','title', 'content', 'image_path', 'date_start', 'date_end'];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the reactions for the post.
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }
    
    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
    
    /**
     * Get all comments for the post including replies.
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
