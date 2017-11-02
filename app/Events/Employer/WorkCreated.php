<?php

namespace App\Events\Employer;

use App\Model\Employer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Model\Works;
use Illuminate\Support\Facades\Log;

class WorkCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $work;
    public $employer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Works $work,Employer $employer)
    {
        $this->work = $work;
        $this->employer = $employer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('employer.'.$this->work->employer_id);
    }
}
