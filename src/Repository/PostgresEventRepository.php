<?php

namespace App\Repository;

use PDO;
use App\Entity\Event;

class PostgresEventRepository implements EventRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findById(int $id): ?Event {
        $statement = $this->pdo->prepare(
            'SELECT * FROM events WHERE id = :id'
        );

        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $id = $row['id'];
            $performer = $row['performer'];
            $date = $row['date'];
            $location = $row['location'];
            $event = new Event($id, $performer, $date, $location);
            return $event;
        } else {
            return null;
        }
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

    public function create(string $performer, string $date, string $location): Event {
        $statement = $this->pdo->prepare(
            'INSERT INTO events (performer, date, location)
            VALUES (:performer, :date, :location)
            RETURNING id'
        );

        $statement->execute([
            'performer' => $performer,
            'date' => $date,
            'location' => $location
        ]);

        $id = (int) $statement->fetchColumn();
        $event = new Event($id, $performer, $date, $location);
        return $event;
    }
}