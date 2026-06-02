<?php


declare(strict_types=1);

namespace Core\Contracts;


use PDO;

interface DatabaseDriver{
    public function connect():PDO;
}