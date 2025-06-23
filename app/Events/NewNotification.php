<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $count;
    public $notifications;
    public $toast;

    public function __construct($data)
    {
        $this->count = $data['count'];
        $this->notifications = $data['notifications'];
        $this->toast = $data['toast'];
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }
}