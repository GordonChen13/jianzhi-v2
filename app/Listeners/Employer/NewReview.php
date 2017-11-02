<?php

namespace App\Listeners\Employer;

use App\Events\User\WorkReviewed;
use App\Model\Employer;
use App\Model\User;
use App\Model\Works;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewReview
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
     * @param  WorkReviewed  $event
     * @return void
     */
    public function handle(WorkReviewed $event)
    {
        $review = $event->review;
        $user = User::find($review->user_id);
        $work = Works::find($review->work_id);
        $employer = Employer::find($review->employer_id);
        $notification_type = '评价了兼职';
        $notification = serialize(new Notification($user,$work,$notification_type));
        Redis::lpush('employer:'.$employer->id.':notifications:unread',$notification);
        $followers = $user->userFollowers()->get();
        $action_type = 'ur1';
        $activity = new Activity($user->id,$review->id,$action_type);
        $serialized_activity = serialize($activity);
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
        if (Redis::get('user:'.$employer->id.':online')) {
            Pusher::trigger('employer.' . $employer->id, 'App\Events\NewReview', ['user' => $user,'review' => $review,'work' => $work]);
        }
        $user_action = DB::table('user_action')->insert(['user_id' => $user->id,'to_id' => $review->id,'type' => 'ur1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
    }
}
