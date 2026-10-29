
<?php

require_once __DIR__ . '/vendor/autoload.php';

$pdo = new PDO(
    'pgsql:host=localhost;port=5432;dbname=eventforge',
    'ivanovrolls',
    ''
);

$repository = new App\Repository\PostgresEventRepository($pdo);

var_dump($repository->findById(3));
var_dump($repository->findById(999999));
