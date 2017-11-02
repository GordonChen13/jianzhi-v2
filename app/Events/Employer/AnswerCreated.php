<?php

namespace App\Events\Employer;

use App\Model\Answers;
use App\Model\Employer;
use App\Model\Works;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AnswerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $answer;
    public $work;
    public $employer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Answers $answer, Works $work, Employer $employer)
    {
        $this->answer = $answer;
        $this->work = $work;
        $this->employer = $employer;
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
