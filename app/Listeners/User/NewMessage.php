<?php

namespace App\Listeners\User;

use App\Events\User\MessageSent;
use App\Model\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\Redis;

class NewMessage implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $message = $event->message;
        $from = User::find($message->user_id);
        $to = User::find($message->target_id);
        $count = $to->unReadMessagesFromUser($from->id)->count();
        $from['un_read_messages_count'] = $count;
        if (Redis::get('user:'.$to->id.':online')) {
            Pusher::trigger('user.'.$to->id, 'App\Events\NewMessage', ['from' => $from, 'message' => $message]);
        }
    }
}
