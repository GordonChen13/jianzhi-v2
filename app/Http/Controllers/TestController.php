<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class TestController {
    public function __construct() {
        $notifications = Redis::lrange('user:1:notifications:unread',0,-1);
        Redis::lpush('user:1:notifications',$notifications);
        return "done!";
    }
}