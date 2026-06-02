<?php

declare(strict_types=1);

namespace Core\Database;


use Core\Contracts\DatabaseDriver;
use PDO;
use PDOException;


//connect mysql db using PDO :D if wala then create new db


final class MySQLDriver implements DatabaseDriver
{
    public function __construct(
        private readonly string $host,
        private readonly string $dbname,
        private readonly string $username,
        private readonly string $password,
        private readonly string $charset = 'utf8mb4',
        private readonly bool $createDatabase = true,
    ) {
    }

    private function connectDB(?string $database): PDO { //let null incase db is not exists yet
        $dsn = $database === null
            ? sprintf('mysql:host=%s;charset=%s', $this->host, $this->charset) //null
            : sprintf('mysql:host=%s;dbname=%s;charset=%s', $this->host, $database, $this->charset); //not null, pass dbname

        $pdo = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //ARON MAG PAASS OG EXCEPTION IF MAG ERRORS :dddd
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //PARA LIMPYO ANG IPASA
        ]);

        return $pdo;
    }

    private function connectServer(): PDO{
        return $this->connectDB(null);
    }

    private function checkDbIsExists(): void {
        $pdo = $this->connectServer();
        $safe_name = str_replace('`', '``', $this->dbname); //PARA SAFE ANG DBNAME
        $pdo->exec(
            "CREATE DATABASE IF NOT EXISTS `{$safe_name}` CHARACTER SET {$this->charset} COLLATE {$this->charset}_unicode_ci",
        );
    }

    public function connect(): PDO{
        try{
            return $this->connectDB($this->dbname);
        } catch (PDOException $exception) {
            if (!$this->createDatabase || !$this->isUnknownDatabase($exception)) {
                throw $this->wrap($exception);
            }

            $this->checkDbIsExists();
            return $this->connectDB($this->dbname);
        }
    }


    private function isUnknownDatabase(PDOException $exception): bool {
        return str_contains($exception->getMessage(), 'Unknown database');
    }



     private function wrap(PDOException $exception): PDOException {
        if (str_contains($exception->getMessage(), 'Access denied.')) {
            return new PDOException(
                'MySQL access denied. Update credentials in config/app.',
                (int) $exception->getCode(),
                $exception,
            );
        }

        if (str_contains($exception->getMessage(), 'Connection refused.') || str_contains($exception->getMessage(), 'actively refused.')) {
            return new PDOException(
                'Cannot reach MySQL. Start your MySQL service (XAMPP/Laragon/WAMP) and try again.',
                (int) $exception->getCode(),
                $exception,
            );
        }

        return $exception;
    }
 
}