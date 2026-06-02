<?php

declare(strict_types=1);

namespace Core\Database;

use Core\Contracts\DatabaseDriver;
use PDO;
use PDOStatement;



final class Database{
    private readonly PDO $pdo;

    public function __construct(DatabaseDriver $driver){
        $this->pdo = $driver->connect();
    }


    public function pdo():PDO{
        return $this->pdo;
    }


    public function query(string $sql, array $params = []): PDOStatement{ //prep and execute query
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement;
    }


    public function fetchAll(string $sql, array $params = []): array{ //fetch all rows as associative array
        return $this->query($sql, $params)->fetchAll();
    }



    public function fetchOne(string $sql, array $params = []): ?array{ //fetch single row as associative array
        $row = $this->query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public function lastInsertId(): string{ //get last inserted id
        return $this->pdo->lastInsertId();
    }
}