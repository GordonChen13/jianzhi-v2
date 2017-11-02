<?php

namespace App\Listeners\User;

use App\Events\User\UserInvited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;

class NewInvite implements ShouldQueue
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
     * @param  UserInvited  $event
     * @return void
     */
    public function handle(UserInvited $event)
    {
        $from = $event->from;
        $to = $event->to;
        $work = $event->work;
        if ($from->id == $work->employer_id) {
            $type = '雇主';
        } else {
            $type = '';
        }
        $notification_type = '邀请你一起做兼职';
        $notification = serialize(new Notification($from,$work,$notification_type));
        Redis::lpush('user:'.$to->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$to->id.':online')) {
            Pusher::trigger('user.' . $to->id, 'App\Events\NewInvite', ['from' => $from,'work' => $work,'type' => $type]);
        }
    }
}
