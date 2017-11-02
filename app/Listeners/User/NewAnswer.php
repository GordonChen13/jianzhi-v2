<?php

namespace App\Listeners\User;

use App\Events\Employer\AnswerCreated;
use App\Model\User;
use App\Model\Employer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Notification;
use App\Notifications\Activity;
use Illuminate\Support\Facades\Redis;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewAnswer implements ShouldQueue
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
     * @param  AnswerCreated  $event
     * @return void
     */
    public function handle(AnswerCreated $event)
    {
        $answer = $event->answer;
        $work = $event->work;
        $employer = $event->employer;
        $employer = Employer::find($work->employer_id);
        $question = $answer->question;
        $employer_action = DB::table('employer_action')->insert(['employer_id' => $employer->id,'to_id' => $answer->id,'type' => 'ea1','created_at' => Carbon::now(),'updated_at' => Carbon::now()]);
        $user = User::find($question->user_id);
        $followers = $employer->userFollowers()->get();
        $notification_type = '回复了咨询';
        $notification = serialize(new Notification($employer,$work,$notification_type));
        $action_type = 'ea1';
        $activity = new Activity($employer->id,$answer->id,$action_type);
        $serialized_activity = serialize($activity);
        Redis::lpush('user:'.$user->id.':notifications:unread',$notification);
        if (Redis::get('user:'.$user->id.':online')) {
            Pusher::trigger('user.' . $user->id, 'App\Events\NewAnswer', ['answer' => $answer,'work' => $work,'employer' => $employer]);
        }
        $wantAnswerUsers = $question->wantAnswerUsers()->get();
        foreach ($wantAnswerUsers as $want_user) {
            Redis::lpush('user:'.$want_user->id.':notifications:unread',$notification);
            if (Redis::get('user:'.$want_user->id.':online')) {
                Pusher::trigger('user.' . $want_user->id, 'App\Events\NewAnswer', ['answer' => $answer,'work' => $work,'employer' => $employer]);
            }
        }
        foreach ($followers as $follower) {
            Redis::lpush('user:'.$follower->id.':activities',$serialized_activity);
        }
    }
}
