<?php
/**
 * Created by PhpStorm.
 * User: chen
 * Date: 17-10-9
 * Time: 上午1:28
 */
namespace App\Repositories\Contracts;

interface NotificationRepositoryInterface {

    public function all();

    public function unRead();

    public function markAsRead();

}