<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Models\Category;
use Core\Contracts\RepositoryInterface;


final class CategoryRepository implements RepositoryInterface{


    public function __construct(private readonly Category $category)
    {
    }

    public function all(): array{
        return $this->category->all('name ASC');
    }

    public function find(int $id): ?array {
        return $this->category->find($id);
    }


    public function create(array $id): int {
        return $this->category->insert($id);
    }

    public function update(int $id, array $data): bool {
        return $this->category->update($id, $data);
    }

    public function delete(int $id): bool {
        return $this->category->remove($id);
    }



}
