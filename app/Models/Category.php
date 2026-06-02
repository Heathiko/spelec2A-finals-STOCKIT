<?php

declare(strict_types=1);

namespace App\Models;


use Core\Database\Model;

final class Category extends Model{
    protected function table(): string{
        return 'categories';
    }
}
