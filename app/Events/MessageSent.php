<?php

namespace App\Events;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversation;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, Conversation $conversation, User $user)
    {
        $this->message = $message;
        $this->conversation = $conversation;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->conversation->id),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Load reply information if needed
        if ($this->message->reply_to) {
            $this->message->load('replyToMessage.user');
        }
        
        // Load affected user for system messages
        if ($this->message->is_system && $this->message->affected_user_id) {
            $this->message->load('affectedUser');
        }
        
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'attachment' => $this->message->attachment ? asset('storage/' . $this->message->attachment) : null,
            'attachment_name' => $this->message->attachment_name,
            'attachment_type' => $this->message->attachment_type,
            'is_read' => $this->message->is_read,
            'created_at' => $this->message->created_at->toIso8601String(),
            'user_id' => $this->user->id,
            'user_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'user_avatar' => $this->user->adminlte_image(),
            'reply_to' => $this->message->reply_to,
            'reply_to_message' => $this->message->reply_to && $this->message->replyToMessage ? [
                'id' => $this->message->replyToMessage->id,
                'body' => $this->message->replyToMessage->body,
                'attachment_name' => $this->message->replyToMessage->attachment_name,
                'user_name' => $this->message->replyToMessage->user->first_name . ' ' . $this->message->replyToMessage->user->last_name,
            ] : null,
            'is_system' => $this->message->is_system,
            'system_action' => $this->message->system_action,
            'affected_user_id' => $this->message->affected_user_id,
            'affected_user' => $this->message->affectedUser ? [
                'id' => $this->message->affectedUser->id,
                'first_name' => $this->message->affectedUser->first_name,
                'last_name' => $this->message->affectedUser->last_name,
                'avatar' => $this->message->affectedUser->adminlte_image(),
            ] : null,
        ];
    }
}
