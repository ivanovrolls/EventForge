<?php
namespace App\Repository;

use App\Entity\Event;

interface EventRepositoryInterface {
    public function findAll() : array;
    public function findById(int $id): ?Event;
}

/* Interfaces are useful for ensuring a standard is followed across all defined
that implement the interface - it is a shared contract. It makes classes interchangeable
across the project if necessary, or even across different projects if they follow the same
standard. It is like defining a convention.  */