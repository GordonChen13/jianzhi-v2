<?php

namespace App\Listeners\User;

use App\Events\Employer\UserReviewed;
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
     * @param  UserReviewed  $event
     * @return void
     */
    public function handle(UserReviewed $event)
    {
        $review = $event->review;
        $user = User::find($review->user_id);
        $work = Works::find($review->work_id);
        $employer = Employer::find($review->employer_id);
        $notification_type = '评价了你';
        $notification = serialize(new Notification($employer,$work,$notification_type));
        Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
        $followers = $employer->userFollowers()->get();
        $action_type = 'er1';
        $activity = new Activity($employer->id,$review->id,$action_type);
        $serialized_activity = serialize($activity);
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
        if (Redis::get('user:'.$user->id.':online')) {
            Pusher::trigger('user.' . $user->id, 'App\Events\NewReview', ['employer' => $employer,'review' => $review,'work' => $work]);
        }
        $employer_action = DB::table('employer_action')->insert(['employer_id' => $employer->id,'to_id' => $review->id,'type' => 'er1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
    }
}
