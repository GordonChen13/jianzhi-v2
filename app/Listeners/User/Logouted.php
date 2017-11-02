<?php

namespace App\Listeners\User;

use App\Events\User\Logout;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Logouted
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $user = $event->user;
        $onlineUsers = Redis::srem('users:online',$user->id);
        $online = Redis::del('user:'.$user->id.':online');
    }
}
