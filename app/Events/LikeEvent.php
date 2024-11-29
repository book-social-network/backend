<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeEvent implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;
    public $postId;
    public $quantity;
    /**
     * Create a new event instance.
     */
    public function __construct($postId, $quantity)
    {
        $this->postId = $postId;
        $this->quantity = $quantity;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->postId);
    }
    public function broadcastAs()
    {
        return 'like-post';
    }
}
