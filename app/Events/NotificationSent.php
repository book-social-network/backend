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
    public $message;
    public $userId;
    public function __construct($message,$userId)
    {
        $this->userId=$userId;
        $this->message=$message;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'notification-event';
    }
}
