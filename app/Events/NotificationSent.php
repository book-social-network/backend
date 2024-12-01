<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\userId;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $userId,$notification;
    public function __construct($notifications,$userId)
    {
        $this->userId=$userId;
        $this->notification=$notifications;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'notification-event';
    }
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
            'userId' => $this->userId
        ];
    }
}
