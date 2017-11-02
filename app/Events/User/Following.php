<?php

namespace App\Events\User;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Following
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from_id;
    public $to_id;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($from_id, $to_id, $type)
    {
        $this->from_id = $from_id;
        $this->to_id = $to_id;
        $this->type = $type;
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
