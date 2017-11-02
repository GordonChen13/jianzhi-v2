<?php

namespace App\Events\User;

use App\Model\User;
use App\Model\Works;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WorkUnApplied
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $work;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Works $work, User $user)
    {
        $this->work = $work;
        $this->user = $user;
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
