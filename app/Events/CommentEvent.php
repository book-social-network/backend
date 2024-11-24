<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    /**
     * Create a new event instance.
     */
    public $postId;
    public $comment;
    public function __construct($postId, $comment)
    {
        $this->postId = $postId; // ID của bài post
        $this->comment = $comment; // Nội dung comment
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('post.' . $this->postId);
    }
    public function broadcastAs()
    {
        return 'comment-post';
    }
}
