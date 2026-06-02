<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Models\Product;
use Core\Contracts\RepositoryInterface;


final class ProductRepository implements RepositoryInterface {
    public function __construct(private readonly Product $product){}


    //with category
    public function all(): array {
        return $this->product->withCategories();
    }

    //ngitaa wala
    public function find(int $id): ?array{
        return $this->product->findWithCategory($id);
    }

    public function create (array $data): int {
        return $this->product->insert($data);
    }


    public function update(int $id, array $data): bool {
        return $this->product->update($id, $data);
    }

    public function delete (int $id): bool {
        return $this->product->remove($id);





















    }


}