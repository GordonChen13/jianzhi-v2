<?php

namespace App\Listeners\User;

use App\Events\Employer\WorkCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use App\Notifications\Activity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\Notification;
use Vinkla\Pusher\Facades\Pusher;

class NewWork implements ShouldQueue
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
     * @param  WorkCreated  $event
     * @return void
     */
    public function handle(WorkCreated $event)
    {
        $work = $event->work;
        $employer = $event->employer;
        $followers = $employer->userFollowers()->get();
        $employer_action = DB::table('employer_action')->insert(['employer_id' => $employer->id,'to_id' => $work->id,'type' => 'ew1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        $notification_type = '发布了新的兼职';
        $notification = serialize(new Notification($employer,$work,$notification_type));
        $action_type = 'ew1';
        $activity = new Activity($employer->id,$work->id,$action_type);
        $serialized_activity = serialize($activity);
        foreach ($followers as $user) {
            Redis::lpush('user:'.$user->id.':activities',$serialized_activity);
            Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
            if (Redis::get('user:'.$user->id.':online')) {
                Pusher::trigger('user.' . $user->id, 'App\Events\NewWork', ['work' => $work,'employer' => $employer]);
            }
        }
    }
}
