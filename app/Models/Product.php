<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Model;


final class Product extends Model{
    protected function table(): string{
        return 'products';
    }


    public function withCategories():array{
        return $this->database->fetchAll(
            'SELECT p.*, c.name AS category_name
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             ORDER BY p.id DESC',
        );
    }

    public function findWithCategory(int $id): ?array{
        return $this->database->fetchOne(
            'SELECT p.*, c.name AS category_name
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id
             LIMIT 1',
            ['id' => $id],
        );
    }
}