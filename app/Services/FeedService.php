<?php

namespace App\Services;

use App\Model\User;
use App\Repositories\FeedRepository;

class FeedService {

    public $feeds;
    public $repository;

    public function __construct(User $user) {
        $this->repository = new FeedRepository($user);
    }

    public function getFeeds($page = 1) {
        return $this->repository->getFeeds($page);
    }
}