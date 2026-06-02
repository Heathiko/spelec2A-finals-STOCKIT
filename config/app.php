<?php

declare(strict_types=1);

return [
    'name' => 'Stockit Inventory',
    'debug' => true,
    'database' => [
        'driver' => 'auto',
        'sqlite' => [
            'path' => dirname(__DIR__) . '/database/inventory.sqlite',
        ],
        'mysql' => [
            'host' => 'localhost',
            'dbname' => 'inventory_db',
            'username' => 'root',
            'password' => 'rootPass12345',
            'charset' => 'utf8mb4',
            'create_database' => true,
        ],
    ],
];
