<?php
header('Content-Type: application/json');

$events = [
    [
        'id' => 1,
        'band' => 'Amon Amarth',
        'date' =>  '18/11/2026',
        'location' => 'Madrid'
    ],
    [
        'id' => 2,
        'band' => 'Clutch',
        'date' =>  '12/12/2026',
        'location' => 'Southampton'
    ],
    [
        'id' => 3,
        'band' => 'Toxaemia',
        'date' =>  '20/03/2027',
        'location' => 'Gothenburg'
    ],
];

$message = [
    'message' => 'Welcome to EventForge'
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_SERVER['REQUEST_URI'] === "/") {
        echo json_encode($message);
    } elseif ($_SERVER['REQUEST_URI'] == "/api/events") {
        echo json_encode($events);
    }
}
