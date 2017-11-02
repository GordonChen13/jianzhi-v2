<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-9
 * Time: 上午1:48
 */

namespace App\Repositories;

use App\Model\User;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class UserNotificationRepository implements NotificationRepositoryInterface {

    public $user;
    public $unReadNotifications;
    public $allNotifications;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function all() {
        $allNotifications = Redis::lrange('user:'.$this->user->id.':notifications',0,-1);
        $this->allNotifications = array_map("unSerialize",$allNotifications);
        $notifications = collect($this->allNotifications);
        $this->allNotifications = $notifications->groupBy(function($item) {
            $date = Carbon::parse($item->created_at);
            return $date->toDateString();
        });
        return $this->allNotifications;
    }

    public function unRead() {
        $unReadNotifications = Redis::lrange('user:'.$this->user->id.':notifications:unread',0,-1);
        $this->unReadNotifications = array_map("unSerialize",$unReadNotifications);
        return $this->unReadNotifications;
    }

    public function markAsRead() {
        $unReadNotifications = Redis::lrange('user:'.$this->user->id.':notifications:unread',0,-1);
        if (count($unReadNotifications) > 0) {
            $addedResult = Redis::lpush('user:'.$this->user->id.':notifications',$unReadNotifications);
            $removeUnRead = Redis::del('user:'.$this->user->id.':notifications:unread');
        }
    }

    public function unSerialize($item) {
        return unserialize($item);
    }
}