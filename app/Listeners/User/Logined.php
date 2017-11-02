<?php

namespace App\Listeners\User;

use App\Events\User\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class Logined
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $onlineUsers = Redis::sadd('users:online',$user->id);
        $online = Redis::setex('user:'.$user->id.':online',3600,1);
    }
}
