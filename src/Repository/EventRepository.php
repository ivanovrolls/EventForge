<?php

namespace App\Repository;
use App\Entity\Event;

class EventRepository implements EventRepositoryInterface {
    public function findAll() : array {
        $events = [ //array keys not necessary, as this is just simpler
        //especially when you would want to save memory if the program scales
            new Event(0, 'Amon Amarth', '18/11/2026', 'Madrid'),
            new Event(1, 'Clutch', '12/12/2026', 'Southampton'),
            new Event(2, 'Toxaemia', '20/03/2027', 'Gothenburg')
        ];

        return $events;
    }
}