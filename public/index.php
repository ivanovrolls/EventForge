
<?php

use App\Repository\PostgresEventRepository;
use App\Controller\EventController;
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO( #pdo objects literally represent the active connection
    #not just configuration - this literally attempts to establish a connection
        'pgsql:host=localhost;port=5432;dbname=eventforge',
        'ivanovrolls',
        ''
    );

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
        $repo = new PostgresEventRepository($pdo);
        $eventCon = new EventController($repo);

        if ($method === 'GET') {
            echo json_encode($eventCon->index());
        } elseif ($method === 'POST') {
            $request = json_decode(file_get_contents('php://input'), true);

            if (!is_array($request)) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Bad Request'
                ]);
                exit;
            }

            $required_fields = ['performer', 'date', 'location'];

            foreach ($required_fields as $field) {
                if (
                    !isset($request[$field]) || //field is not set or
                    !is_string($request[$field]) || //field is not correct data tyoe or
                    trim($request[$field]) === '' //field is empty
                ) {
                    http_response_code(400);
                    echo json_encode([
                        'error' => 'Bad Request'
                    ]);
                    exit;
                }
            }

            $date = $request['date'];

            $parsedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

            if (
                $parsedDate === false ||
                $parsedDate->format('Y-m-d') !== $date
            ) {
                http_response_code(400);
                echo json_encode([
                    'error' => 'Invalid date. Expected format: YYYY-MM-DD'
                ]);
                exit;
            }

            //now input is validated so create the event
            $event = $eventCon->create(
                $request['performer'],
                $request['date'],
                $request['location']
            );

            //success code
            http_response_code(201);
            echo json_encode($event);

        } else {
            header('Allow: GET, POST');
            http_response_code(405);
            echo json_encode([
                'error' => 'Method Not Allowed'
            ]);
        }
    } elseif (preg_match('#^/api/events/([0-9]+)$#', $path, $matches)) { //PHP functions allow you to assign values to variables passed to them
        $id = (int) $matches[1];

        if ($method === 'GET') {

            $repo = new PostgresEventRepository($pdo);
            $eventCon = new EventController($repo);
            $event = $eventCon->show($id);
            if ($event !== null) {
                echo json_encode($event);
            } else {
                http_response_code(404);
                echo json_encode([
                    'error' => 'Not Found'
                ]);
            }
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
} catch (PDOException $e) {
    error_log($e->getMessage());

    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error'
    ]);
}
