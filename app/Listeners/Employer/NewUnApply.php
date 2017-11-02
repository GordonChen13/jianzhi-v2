<?php

namespace App\Listeners\Employer;

use App\Events\User\WorkUnApplied;
use App\Model\Employer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;

class NewUnApply implements ShouldQueue
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
     * @param  WorkUnApplied  $event
     * @return void
     */
    public function handle(WorkUnApplied $event)
    {
        $user = $event->user;
        $work = $event->work;
        $employer = Employer::find($work->employer_id);
        $notification_type = '取消了申请';
        $notification = serialize(new Notification($user,$work,$notification_type));
        Redis::lpush('employer:'.$employer->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$employer->id.':online')) {
            Pusher::trigger('employer.' . $employer->id, 'App\Events\NewUnApply', ['user' => $user,'work' => $work]);
        }
    }
}
