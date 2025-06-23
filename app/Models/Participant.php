<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'last_read',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_read' => 'datetime',
    ];

    /**
     * Get the conversation that owns the participant.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user that owns the participant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark conversation as read by this participant
     *
     * @return $this
     */
    public function markAsRead()
    {
        $this->last_read = now();
        $this->save();

        return $this;
    }
}
