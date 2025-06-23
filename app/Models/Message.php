<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * System message action types
     */
    const ACTION_USER_ADDED = 'user_added';
    const ACTION_USER_LEFT = 'user_left';
    const ACTION_USER_REMOVED = 'user_removed';
    const ACTION_GROUP_CREATED = 'group_created';
    const ACTION_GROUP_RENAMED = 'group_renamed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'attachment',
        'attachment_type',
        'attachment_name',
        'is_read',
        'read_at',
        'reply_to',
        'is_system',
        'system_action',
        'affected_user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_system' => 'boolean',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user that owns the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message this message is replying to.
     */
    public function replyToMessage()
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    /**
     * Get replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    /**
     * Get the affected user (for system messages)
     */
    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_user_id');
    }

    /**
     * Mark the message as read.
     *
     * @return $this
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }

        return $this;
    }

    /**
     * Check if the message has an attachment.
     *
     * @return bool
     */
    public function hasAttachment()
    {
        return !is_null($this->attachment);
    }

    /**
     * Check if the message is a system message.
     *
     * @return bool
     */
    public function isSystemMessage()
    {
        return $this->is_system;
    }

    /**
     * Create a system message when a user is added to a group
     *
     * @param int $conversationId
     * @param int $addedByUserId
     * @param int $addedUserId
     * @return static
     */
    public static function createUserAddedSystemMessage($conversationId, $addedByUserId, $addedUserId)
    {
        // Create a new message instance and set properties manually
        $message = new self();
        $message->conversation_id = $conversationId;
        $message->user_id = $addedByUserId;
        $message->body = null; // No body text for this system message
        $message->is_system = true;
        $message->system_action = 'user_added'; // Use string directly instead of constant
        $message->affected_user_id = $addedUserId;
        $message->save();
        
        return $message;
    }

    /**
     * Create a welcome system message for a new group
     *
     * @param int $conversationId
     * @param int $createdByUserId
     * @return static
     */
    public static function createGroupCreatedSystemMessage($conversationId, $createdByUserId)
    {
        // Create a new message instance and set properties manually
        $message = new self();
        $message->conversation_id = $conversationId;
        $message->user_id = $createdByUserId;
        $message->body = 'Group conversation created. Welcome!';
        $message->is_system = true;
        $message->system_action = 'group_created'; // Use string directly instead of constant
        $message->save();
        
        return $message;
    }

    /**
     * Format system message text based on action type
     *
     * @return string|null
     */
    public function getFormattedSystemMessage()
    {
        if (!$this->is_system) {
            return null;
        }

        $userName = $this->user->first_name . ' ' . $this->user->last_name;
        
        switch ($this->system_action) {
            case self::ACTION_USER_ADDED:
                if ($this->affectedUser) {
                    $affectedName = $this->affectedUser->first_name . ' ' . $this->affectedUser->last_name;
                    return "{$userName} added {$affectedName} to the group";
                }
                return "{$userName} added a user to the group";
                
            case self::ACTION_USER_LEFT:
                return "{$userName} left the group";
                
            case self::ACTION_USER_REMOVED:
                if ($this->affectedUser) {
                    $affectedName = $this->affectedUser->first_name . ' ' . $this->affectedUser->last_name;
                    return "{$userName} removed {$affectedName} from the group";
                }
                return "{$userName} removed a user from the group";
                
            case self::ACTION_GROUP_CREATED:
                return $this->body ?: "{$userName} created this group";
                
            case self::ACTION_GROUP_RENAMED:
                return "{$userName} renamed the group";
                
            default:
                return $this->body;
        }
    }
}
