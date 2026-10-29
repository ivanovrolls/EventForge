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
        if ($method === 'GET') {
            $repo = new PostgresEventRepository($pdo);
            $eventCon = new EventController($repo);
            echo json_encode($eventCon->index());
        } else {
            header('Allow: GET');
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