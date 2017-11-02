<?php

namespace App\Listeners\User;

use App\Events\Employer\DenyApply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\Redis;
use App\Model\Employer;
use App\Notifications\Notification;

class ApplyDenied implements ShouldQueue
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
     * @param  DenyApply  $event
     * @return void
     */
    public function handle(DenyApply $event)
    {
        $work = $event->work;
        $user = $event->user;
        $employer = Employer::find($work->employer_id);
        $type = '拒绝了你的申请';
        $notification = serialize(new Notification($employer,$work,$type));
        Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$user->id.':online')) {
            Pusher::trigger('user.'.$user->id,'App\Events\ApplyDenied',['data' => $work]);
        }
    }
}
