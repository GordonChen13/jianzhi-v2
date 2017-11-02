<?php

namespace App\Services;

use App\Model\User;
use App\Repositories\UserNotificationRepository;

class UserNotificationService {

    public $notifications;
    public $repository;

    public function __construct(User $user) {
        $this->repository = new UserNotificationRepository($user);
    }

    public function getAll() {
        return $this->repository->all();
    }

    public function getUnRead() {
        return $this->repository->unRead();
    }

    public function markAsRead() {
        return $this->repository->markAsRead();
    }

}