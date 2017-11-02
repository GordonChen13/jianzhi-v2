<?php

namespace App\Listeners\Employer;

use App\Events\User\QuestionCreated;
use App\Model\Employer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewQuestion implements ShouldQueue
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
     * @param  QuestionCreated  $event
     * @return void
     */
    public function handle(QuestionCreated $event)
    {
        $question = $event->question;
        $work = $event->work;
        $user = $event->user;
        $user_action = DB::table('user_action')->insert(['user_id' => $user->id,'to_id' => $question->id,'type' => 'uq1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        $employer = Employer::find($work->employer_id);
        $followers = $user->userFollowers()->get();
        $notification_type = '提出了新的咨询';
        $notification = serialize(new Notification($user,$work,$notification_type));
        $action_type = 'uq1';
        $activity = new Activity($user->id,$question->id,$action_type);
        $serialized_activity = serialize($activity);
        Redis::lpush('employer:'.$employer->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$employer->id.':online')) {
            Pusher::trigger('employer.' . $employer->id, 'App\Events\NewQuestion', ['question' => $question,'work' => $work,'user' => $user]);
        }
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
        $user_action = DB::table('user_action')->insert(['user_id' => $user->id,'to_id' => $question->id,'type' => 'uq1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
    }
}
