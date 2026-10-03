<?php

class Event implements JsonSerializable
{
    private $id;
    private $performer;
    private $date;
    private $location;

    //constructor
    public function __construct($id, $performer, $date, $location) {
        $this->id = $id;
        $this->performer = $performer;
        $this->date = $date;
        $this->location = $location;
    }

    //getters
    public function getId() {
        return $this->id;
    }
    public function getPerformer() {
        return $this->performer;
    }
    public function getDate() {
        return $this->date;
    }
    public function getLocation() {
        return $this->location;
    }

    //interface
    public function jsonSerialize():mixed {
        return [
            'id' => $this->getId(),
            'performer' => $this->getPerformer(),
            'date' => $this->getDate(),
            'location' => $this->getLocation()
        ];
    }
}