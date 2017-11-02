<?php

namespace App\Listeners\User;

use App\Events\Employer\ReviewReplied;
use App\Model\Employer;
use App\Model\User;
use App\Model\WorkReviews;
use App\Model\Works;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewReply implements ShouldQueue
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
     * @param  ReviewReplied  $event
     * @return void
     */
    public function handle(ReviewReplied $event)
    {
        $reply = $event->reply;
        $review = WorkReviews::find($reply->review_id);
        $work = Works::find($review->work_id);
        $employer = Employer::find($reply->employer_id);
        $user = User::find($review->user_id);
        $notification_type = '对评价做出了回应';
        $notification = serialize(new Notification($employer,$work,$notification_type));
        Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
        $followers = $employer->userFollowers()->get();
        $action_type = 'er2';
        $activity = new Activity($employer->id,$reply->id,$action_type);
        $serialized_activity = serialize($activity);
        $employer_action = DB::table('employer_action')->insert(['employer_id' => $employer->id,'to_id' => $reply->id,'type' => 'er2','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
        if (Redis::get('user:'.$user->id.':online')) {
            Pusher::trigger('user.' . $user->id, 'App\Events\NewReply', ['employer' => $employer,'review' => $review,'reply' => $reply,'work' => $work]);
        }
    }
}
