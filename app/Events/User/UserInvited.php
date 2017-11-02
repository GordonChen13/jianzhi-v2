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

class UserInvited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from;
    public $to;
    public $work;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $from, User $to, Works $work)
    {
        $this->from = $from;
        $this->to = $to;
        $this->work = $work;
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
