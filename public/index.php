<?php

use App\Repository\EventRepository;
use App\Controller\EventController;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$message = [
    'message' => 'Welcome to EventForge'
];


<?php

use App\Repository\EventRepository;
use App\Controller\EventController;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$message = [
    'message' => 'Welcome to EventForge'
];

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/') {
    if ($method === 'GET') {
        echo json_encode($message);
    } else {
        header('Allow: GET');
        http_response_code(405);
        echo json_encode([
            'error' => 'Method Not Allowed'
        ]);
    }
} elseif ($path === '/api/events') {
    if ($method === 'GET') {
        $repo = new EventRepository();
        $eventCon = new EventController($repo);
        echo json_encode($eventCon->index());
    } else {
        header('Allow: GET');
        http_response_code(405);
        echo json_encode([
            'error' => 'Method Not Allowed'
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Not Found'
    ]);
}

