<?php

namespace App\Services;

use App\Model\Employer;
use App\Repositories\EmployerNotificationRepository;

class EmployerNotificationService {

    public $notifications;
    public $repository;

    public function __construct(Employer $employer) {
        $this->repository = new EmployerNotificationRepository($employer);
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