<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\MessageSent;
use App\Events\ConversationUpdated;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_group',
        'avatar',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_group' => 'boolean',
    ];

    /**
     * Get the participants for the conversation.
     */
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Get the users in this conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'participants')
            ->withPivot('last_read')
            ->withTimestamps();
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message from the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Get the creator of the conversation.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the user is a participant of the conversation.
     *
     * @param int $userId
     * @return bool
     */
    public function hasParticipant($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Get unread messages for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unreadMessages($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->get();
    }

    /**
     * Get unread messages count for a user
     *
     * @param int $userId
     * @return int
     */
    public function unreadMessagesCount($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Add a participant to the conversation.
     *
     * @param int $userId
     * @return \App\Models\Participant
     */
    public function addParticipant($userId)
    {
        return $this->participants()->create([
            'user_id' => $userId,
        ]);
    }

    /**
     * Add a user to a group conversation and create system message
     *
     * @param int $userId User being added
     * @param int $addedById User who is adding
     * @return array The created participant and message
     */
    public function addUserToGroup($userId, $addedById)
    {
        // Only allow adding users to group conversations
        if (!$this->is_group) {
            throw new \Exception('Cannot add users to non-group conversations');
        }

        // Don't add if already a participant
        if ($this->hasParticipant($userId)) {
            return null;
        }

        // Add the participant
        $participant = $this->addParticipant($userId);

        // Create system message
        $message = Message::createUserAddedSystemMessage(
            $this->id,
            $addedById,
            $userId
        );

        // Load users for message broadcasting
        $addedByUser = User::find($addedById);
        $addedUser = User::find($userId);

        // Broadcast the message
        event(new MessageSent($message, $this, $addedByUser));
        
        // Broadcast conversation update to all participants
        event(new ConversationUpdated(
            $this, 
            $addedByUser, 
            ConversationUpdated::TYPE_PARTICIPANT_ADDED, 
            [
                'added_user' => [
                    'id' => $addedUser->id,
                    'name' => $addedUser->first_name . ' ' . $addedUser->last_name
                ]
            ]
        ));

        return [
            'participant' => $participant,
            'message' => $message
        ];
    }

    /**
     * Create a welcome message for this conversation
     * 
     * @param int $userId User creating the welcome message
     * @return \App\Models\Message
     */
    public function createWelcomeMessage($userId)
    {
        // Only for group conversations
        if (!$this->is_group) {
            return null;
        }

        $user = User::find($userId);
        $message = Message::createGroupCreatedSystemMessage($this->id, $userId);
        
        // Broadcast the message
        event(new MessageSent($message, $this, $user));
        
        // Also broadcast conversation creation event
        event(new ConversationUpdated(
            $this, 
            $user, 
            ConversationUpdated::TYPE_CREATED,
            [
                'participants' => $this->participants->count()
            ]
        ));

        return $message;
    }
}
