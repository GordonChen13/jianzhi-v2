<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //user events
        'App\Events\User\Register' => [
            'App\Listeners\User\Registered',
        ],
        'App\Events\User\Login' => [
            'App\Listeners\User\Logined',
        ],
        'App\Events\User\Logout' => [
            'App\Listeners\User\Logouted',
        ],
        'App\Events\User\ApplyWork' => [
            'App\Listeners\Employer\WorkApplied',
        ],
        'App\Events\User\QuestionCreated' => [
            'App\Listeners\Employer\NewQuestion',
        ],
        'App\Events\User\Following' => [
            'App\Listeners\User\NewFollower',
        ],
        'App\Events\User\UnFollowing' => [
            'App\Listeners\User\LostFollower',
        ],
        'App\Events\User\UserInvited' => [
            'App\Listeners\User\NewInvite',
        ],
        'App\Events\User\WorkApplied' => [
            'App\Listeners\Employer\NewApply',
        ],
        'App\Events\User\WorkUnApplied' => [
            'App\Listeners\Employer\NewUnApply',
        ],
        'App\Events\User\WorkReviewed' => [
            'App\Listeners\Employer\NewReview',
        ],
        'App\Events\User\ReviewReplied' => [
            'App\Listeners\Employer\NewReply',
        ],
        'App\Events\User\MessageSent' => [
            'App\Listeners\User\NewMessage',
        ],

        //employer events
        'App\Events\Employer\WorkCreated' => [
            'App\Listeners\User\NewWork',
        ],
        'App\Events\Employer\PassApply' => [
            'App\Listeners\User\ApplyPassed',
        ],
        'App\Events\Employer\DenyApply' => [
            'App\Listeners\User\ApplyDenied',
        ],
        'App\Events\Employer\AnswerCreated' => [
            'App\Listeners\User\NewAnswer',
        ],
        'App\Events\Employer\UserReviewed' => [
            'App\Listeners\User\NewReview',
        ],
        'App\Events\Employer\ReviewReplied' => [
            'App\Listeners\User\NewReply',
        ],

        //work events//employer events
        'App\Events\Work\WorkViewed' => [
            'App\Listeners\Work\NewViewCount',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
