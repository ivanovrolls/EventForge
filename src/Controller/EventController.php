<?php

namespace App\Controller;
use App\Repository\EventRepositoryInterface;

class EventController {
    private EventRepositoryInterface $repository;

    public function __construct(EventRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function index(): array{
        return $this->repository->findAll();
    }
}
