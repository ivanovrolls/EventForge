<?php

header('Content-Type: application/json');

$message = [
    'message' => 'Welcome to EventForge'
];

echo json_encode($message);