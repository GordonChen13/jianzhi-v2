<?php

namespace App\Listeners\Work;

use App\Events\Work\WorkViewed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class NewViewCount
{
    //最大访问次数,缓存达到这个数字再刷新数据库
    const viewLimit = 3;

    //同一用户浏览同一兼职过期时间
    const ipExpireSec = 300;

    //每10个小时让viewLimit失效，避免当兼职结束无人访问后viewLimit仍保存在内存中
    const viewLimitExpireSec = 36000;

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
     * @param  WorkViewed  $event
     * @return void
     */
    public function handle(WorkViewed $event)
    {
        $work = $event->work;
        $ip = $event->ip;
        if (!$this->isIpLimit($work->id,$ip)) {
            $this->updateCacheViewCount($work, $ip);
        }
    }

    /**判断ip是否在已经访问过的redis set中
     * @param $id
     * @param $ip
     * @return boolean
     */
    public function isIpLimit($id,$ip) {
        return Redis::sismember('work:'.$id.':views:ip',$ip);
    }

    public function updateCacheViewCount($work,$ip) {
        $viewCount = Redis::incr('work:'.$work->id.':views:count');
        Redis::expire('work:'.$work->id.':views:count',self::viewLimitExpireSec);
        Redis::sadd('work:'.$work->id.':views:ip',$ip);
        Redis::expire('work:'.$work->id.':views:ip',self::ipExpireSec);
        if ($viewCount == self::viewLimit) {
            $work->page_view += $viewCount;
            $work->save();
            Redis::del('work:'.$work->id.':views:count');
        }
    }
}
