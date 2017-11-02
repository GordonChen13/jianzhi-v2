<?php

/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-6
 * Time: 下午8:16
 */
namespace App\Notifications;

use Carbon\Carbon;

class Notification
{
    public $actionFrom;
    public $actionTo;
    public $type;
    public $created_at;

    public function __construct($actionFrom,$actionTo,$type) {
        $this->actionFrom = $actionFrom;
        $this->actionTo = $actionTo;
        $this->type = $type;
        $this->created_at = Carbon::now();
    }
}