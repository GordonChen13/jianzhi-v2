<?php

namespace App\Events\Work;

use App\Model\Works;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WorkViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $work;
    public $ip;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Works $work, $ip)
    {
        $this->work = $work;
        $this->ip = $ip;
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
