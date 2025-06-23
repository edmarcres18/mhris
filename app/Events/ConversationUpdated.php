<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $user;
    public $updateType;
    public $additionalData;

    const TYPE_CREATED = 'created';
    const TYPE_UPDATED = 'updated';
    const TYPE_PARTICIPANT_ADDED = 'participant_added';
    const TYPE_PARTICIPANT_REMOVED = 'participant_removed';
    const TYPE_RENAMED = 'renamed';

    /**
     * Create a new event instance.
     */
    public function __construct(Conversation $conversation, User $user, string $updateType, array $additionalData = [])
    {
        $this->conversation = $conversation;
        $this->user = $user;
        $this->updateType = $updateType;
        $this->additionalData = $additionalData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->conversation->id),
        ];

        // Also broadcast to all participants' private channels
        foreach ($this->conversation->participants as $participant) {
            $channels[] = new PrivateChannel('user.' . $participant->user_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'conversation.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Load related models for the payload
        $this->conversation->load(['lastMessage', 'lastMessage.user', 'participants.user']);
        
        // Get the name based on conversation type
        $displayName = $this->conversation->is_group 
            ? $this->conversation->name 
            : null;
        
        // Format last message if available
        $lastMessage = null;
        if ($this->conversation->lastMessage) {
            $lastMessage = [
                'id' => $this->conversation->lastMessage->id,
                'body' => $this->conversation->lastMessage->body,
                'created_at' => $this->conversation->lastMessage->created_at->toIso8601String(),
                'user_id' => $this->conversation->lastMessage->user_id,
                'user_name' => $this->conversation->lastMessage->user->first_name . ' ' . $this->conversation->lastMessage->user->last_name,
                'is_system' => $this->conversation->lastMessage->is_system ?? false,
            ];
        }
        
        return [
            'id' => $this->conversation->id,
            'name' => $displayName,
            'is_group' => $this->conversation->is_group,
            'participants_count' => $this->conversation->participants->count(),
            'last_message' => $lastMessage,
            'update_type' => $this->updateType,
            'updated_by' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name . ' ' . $this->user->last_name
            ],
            'additional_data' => $this->additionalData,
            'updated_at' => $this->conversation->updated_at->toIso8601String()
        ];
    }
}
