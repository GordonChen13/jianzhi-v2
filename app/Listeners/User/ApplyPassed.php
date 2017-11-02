<?php

namespace App\Listeners\User;

use App\Events\Employer\PassApply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Vinkla\Pusher\Facades\Pusher;
use App\Notifications\Notification;
use App\Model\Employer;
use Illuminate\Support\Facades\Redis;

class ApplyPassed implements ShouldQueue
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
     * @param  PassApply  $event
     * @return void
     */
    public function handle(PassApply $event)
    {
        $work = $event->work;
        $user = $event->user;
        $employer = Employer::find($work->employer_id);
        $type = '通过了你的申请';
        $notification = serialize(new Notification($employer,$work,$type));
        Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$user->id.':online')) {
            Pusher::trigger('user.' . $user->id, 'App\Events\ApplyPassed', ['data' => $work]);
        }
    }
}
