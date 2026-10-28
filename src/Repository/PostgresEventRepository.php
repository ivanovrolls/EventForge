<?php

namespace App\Repository;

use PDO;
use App\Entity\Event;

class PostgresEventRepository implements EventRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        $statement = $this->pdo->query('SELECT * FROM events ORDER BY date ASC');
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $events = [];
        foreach ($rows as $row) {
            $id = $row['id'];
            $performer = $row['performer'];
            $date = $row['date'];
            $location = $row['location'];
            $event = new Event($id, $performer, $date, $location);
            $events[] = $event;
        }
        
        return $events;
    }
}