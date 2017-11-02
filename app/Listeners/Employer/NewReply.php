<?php

namespace App\Listeners\Employer;

use App\Events\User\ReviewReplied;
use App\Model\Employer;
use App\Model\User;
use App\Model\WorkerReviews;
use App\Model\Works;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $review = WorkerReviews::find($reply->review_id);
        $work = Works::find($review->work_id);
        $user = User::find($reply->user_id);
        $employer = Employer::find($review->employer_id);
        $notification_type = '对评价做出了回应';
        $notification = serialize(new Notification($user,$work,$notification_type));
        Redis::lpush('employer:'.$employer->id.':notifications:unread',$notification);
        $followers = $user->userFollowers()->get();
        $action_type = 'ur2';
        $activity = new Activity($user->id,$reply->id,$action_type);
        $serialized_activity = serialize($activity);
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
        if (Redis::get('user:'.$employer->id.':online')) {
            Pusher::trigger('employer.' . $employer->id, 'App\Events\NewReply', ['user' => $user,'review' => $review,'reply' => $reply,'work' => $work]);
        }

        $user_action = DB::table('user_action')->insert(['user_id' => $user->id,'to_id' => $reply->id,'type' => 'ur2','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
    }
}
