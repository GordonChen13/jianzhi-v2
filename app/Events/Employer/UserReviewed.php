<?php

namespace App\Events\Employer;

use App\Model\WorkerReviews;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserReviewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(WorkerReviews $review)
    {
        $this->review = $review;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
