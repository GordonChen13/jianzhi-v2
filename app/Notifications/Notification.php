<?php

/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-6
 * Time: 下午8:16
 */
namespace App\Notifications;

class Notification
{
    public $actionFrom;
    public $actionTo;
    public $type;

    public function __construct($actionFrom,$actionTo,$type) {
        $this->actionFrom = $actionFrom;
        $this->actionTo = $actionTo;
        $this->type = $type;
    }
}