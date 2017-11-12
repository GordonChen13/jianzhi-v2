<?php

namespace App\Listeners\User;

use App\Events\User\Register;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class Registered
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
     * @param  Register  $event
     * @return void
     */
    public function handle(Register $event)
    {
        $user = $event->user;
        $onlineUsers = Redis::sadd('users:online',$user->id);
        $online = Redis::setex('user:'.$user->id.':online',3600,1);
    }
}
