<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Reaction extends Model
{
    use HasFactory;
    use Loggable;

    protected $fillable = ['user_id', 'post_id', 'type'];

    /**
     * Get the user that owns the reaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the post that owns the reaction.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
