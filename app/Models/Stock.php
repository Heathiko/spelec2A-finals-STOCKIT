<?php

declare(strict_types=1);

namespace App\Models;

use Core\Contracts\ReportableInterface;
use Core\Database\Database;

final class Stock implements ReportableInterface
{
    public function __construct(private readonly Database $database) {}

    public function generateReport(): array
    {
        return $this->database->fetchAll(
            'SELECT
                p.id,
                p.name,
                p.sku,
                c.name AS category_name,
                p.quantity,
                p.price,
                (p.quantity * p.price) AS inventory_value,
                CASE
                    WHEN p.quantity <= 5 THEN \'LOW STOCK\'
                    WHEN p.quantity <= 20 THEN \'MEDIUM STOCK\'
                    ELSE \'HEALTHY STOCK\'
                END AS stock_status
            FROM products p
            INNER JOIN categories c ON c.id = p.category_id
            ORDER BY p.quantity ASC, p.name ASC'
        );
    }
}