<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'user_id', 'type'];
    
    /**
     * Get the comment that owns the reaction.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
    
    /**
     * Get the user that owns the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 