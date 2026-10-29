<?php

namespace App\Controller;
use App\Repository\EventRepositoryInterface;
use App\Entity\Event;

class EventController {
    private EventRepositoryInterface $repository;

    public function __construct(EventRepositoryInterface $repository) {
        $this->repository = $repository;
    }

    public function index(): array{
        return $this->repository->findAll();
    }

    public function show(int $id): ?Event {
        return $this->repository->findById($id);
    }
}
