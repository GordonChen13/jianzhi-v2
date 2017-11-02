<?php

namespace App\Listeners\User;

use App\Events\User\UnFollowing;
use App\Model\Employer;
use App\Model\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LostFollower implements ShouldQueue
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
     * @param  UnFollowing  $event
     * @return void
     */
    public function handle(UnFollowing $event)
    {
        $from_id = $event->from_id;
        $to_id = $event->to_id;
        $type = $event->type;
        if ($type == '11') {
            $from_user = User::find($from_id);
            $to_user = User::find($to_id);
            $notification_type = '取消关注你了';
            $notification = serialize(new Notification($from_user,$to_user,$notification_type));
            Redis::lpush('user:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('user.' . $to_id, 'App\Events\LostFollower', ['from_user' => $from_user,'type' => $type]);
            }
        } else if ($type == '12') {
            $from_user = User::find($from_id);
            $to_employer = Employer::find($to_id);
            $notification_type = '取消关注你了';
            $notification = serialize(new Notification($from_user,$to_employer,$notification_type));
            Redis::lpush('employer:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('employer.' . $to_id, 'App\Events\LostFollower', ['from_user' => $from_user,'type' => $type]);
            }
        } else if ($type == '21') {
            $from_employer = Employer::find($from_id);
            $to_user = User::find($to_id);
            $notification_type = '雇主取消关注你了';
            $notification = serialize(new Notification($from_employer,$to_user,$notification_type));
            Redis::lpush('user:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('user.' . $to_id, 'App\Events\LostFollower', ['from_employer' => $from_employer,'type' => $type]);
            }
        }
    }
}
