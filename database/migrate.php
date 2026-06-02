<?php
declare(strict_types=1);

use Core\Database\Database;

function migrate(Database $database): void{
    $driver = $database->pdo()->getAttribute(PDO::ATTR_DRIVER_NAME);

    if($driver === 'mysql'){
        $database->pdo()->exec(
            'CREATE TABLE IF NOT EXISTS categories(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name varchar(120) NOT NULL unique,
            description TEXT not null
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $database->pdo()->exec(
            'CREATE TABLE IF NOT EXISTS products(
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id INT unsigned not null, 
            name varchar(150) not null,
            sku varchar(100) not null unique,
            description TEXT not null,
            quantity int not null default 0,
            price decimal(10,2) not null default 0,
            FOREIGN KEY (category_id) REFERENCES categories(id)
            ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4'
        );

    } else {
        $database->pdo()->exec(
            'CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT NOT NULL DEFAULT ""
            )'
        );

        $database->pdo()->exec(
            'CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                sku TEXT NOT NULL UNIQUE,
                description TEXT NOT NULL DEFAULT "",
                quantity INTEGER NOT NULL DEFAULT 0,
                price REAL NOT NULL DEFAULT 0,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )'
        );
    }

    $count = (int) $database->fetchOne('SELECT COUNT(*) AS total FROM categories')['total'];
    if($count > 0){
        return;
    }

    $database->query('INSERT INTO categories (name, description) VALUES (:name, :description)', [
        'name' => 'Electronics',
        'description' => 'Devices and Accessories',
    ]);

    $database->query('INSERT INTO categories (name, description) VALUES (:name, :description)', [
        'name' => 'Office Supplies',
        'description' => 'Stationary and Workspace Items',
    ]);

    $database->query('INSERT INTO categories (name, description) VALUES (:name, :description)', [
        'name' => 'Kitchenware',
        'description' => 'Utensils and Cooking Items',
    ]);

    $products = [
        ['category_id' => 1, 'name' => 'Maono Pd100x Dynamic Microphone USB-C/XLR', 'sku' => 'MPDM-001', 'description' => 'Hybrid USB/XLR dynamic microphone designed for gamers, streamers, and podcasters', 'quantity' => 25, 'price' => 2600],
        ['category_id' => 2, 'name' => 'Logitech G Pro X Superlight Wireless Gaming Mouse', 'sku' => 'LGPX-001', 'description' => 'Ultra-lightweight wireless gaming mouse with HERO 25K sensor and LIGHTSPEED technology', 'quantity' => 15, 'price' => 4500],
        ['category_id' => 3, 'name' => 'Moleskine Classic Notebook', 'sku' => 'MCN-001', 'description' => 'Hardcover notebook with acid-free paper, ideal for writing and sketching', 'quantity' => 50, 'price' => 800],
        ['category_id' => 1, 'name' => 'Fellowes Powershred 79Ci Cross-Cut Shredder', 'sku' => 'FPS-001', 'description' => 'Cross-cut shredder with a 10-sheet capacity and a 4.8-gallon bin', 'quantity' => 10, 'price' => 3200],
        ['category_id' => 2, 'name' => 'Instant Pot Duo 7-in-1 Electric Pressure Cooker', 'sku' => 'IPD-001', 'description' => 'Multi-use programmable pressure cooker that can also slow cook, sauté, and more', 'quantity' => 20, 'price' => 5000],
        ['category_id' => 3, 'name' => 'OXO Good Grips Non-Stick Pro Bakeware Set', 'sku' => 'OGG-001', 'description' => '10-piece non-stick bakeware set including cake pans, muffin tins, and cookie sheets', 'quantity' => 30, 'price' => 3500],
    ];

    foreach ($products as $product){
        $database->query(
            'INSERT INTO products(category_id, name, sku, description, quantity, price)
             VALUES (:category_id, :name, :sku, :description, :quantity, :price)',
            $product
        );
    }
}




//ALTER TABLE products 
// ADD COLUMN supplier_id INT UNSIGNED NULL,
// ADD FOREIGN KEY (supplier_id) REFERENCES suppliers(id);