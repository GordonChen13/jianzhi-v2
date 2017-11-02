<?php

namespace App\Listeners\User;

use App\Events\User\Following;
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

class NewFollower implements ShouldQueue
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
     * @param  Following  $event
     * @return void
     */
    public function handle(Following $event)
    {
        $from_id = $event->from_id;
        $to_id = $event->to_id;
        $type = $event->type;
        if ($type == '11') {
            $from_user = User::find($from_id);
            $to_user = User::find($to_id);
            $user_action = DB::table('user_action')->insert(['user_id' => $from_user->id,'to_id' => $to_id,'type' => 'uu1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
            $followers = $from_user->userFollowers()->get();
            $notification_type = '关注了你';
            $notification = serialize(new Notification($from_user,$to_user,$notification_type));
            $action_type = 'uu1';
            $activity = new Activity($from_id,$to_id,$action_type);
            $serialized_activity = serialize($activity);
            Redis::lpush('user:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('user.' . $to_id, 'App\Events\NewFollower', ['from_user' => $from_user,'type' => $type]);
            }
            foreach ($followers as $follower) {
                Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
            }
        } else if ($type == '12') {
            $from_user = User::find($from_id);
            $to_employer = Employer::find($to_id);
            $user_action = DB::table('user_action')->insert(['user_id' => $from_user->id,'to_id' => $to_id,'type' => 'ue1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
            $followers = $from_user->userFollowers()->get();
            $notification_type = '关注了你';
            $notification = serialize(new Notification($from_user,$to_employer,$notification_type));
            $action_type = 'ue1';
            $activity = new Activity($from_id,$to_id,$action_type);
            $serialized_activity = serialize($activity);
            Redis::lpush('employer:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('employer.' . $to_id, 'App\Events\NewFollower', ['from_user' => $from_user,'type' => $type]);
            }
            foreach ($followers as $follower) {
                Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
            }
        } else if ($type == '21') {
            $from_employer = Employer::find($from_id);
            $to_user = User::find($to_id);
            $employer_action = DB::table('employer_action')->insert(['employer_id' => $from_employer->id,'to_id' => $to_id,'type' => 'eu1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
            $followers = $from_employer->userFollowers()->get();
            $notification_type = '雇主关注了你';
            $notification = serialize(new Notification($from_employer,$to_user,$notification_type));
            $action_type = 'eu1';
            $activity = new Activity($from_id,$to_id,$action_type);
            $serialized_activity = serialize($activity);
            Redis::lpush('user:'.$to_id.':notifications:unread',$notification);
            if (Redis::get('user:'.$to_id.':online')) {
                Pusher::trigger('user.' . $to_id, 'App\Events\NewFollower', ['from_employer' => $from_employer,'type' => $type]);
            }
            foreach ($followers as $follower) {
                Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
            }
        }
    }
}
