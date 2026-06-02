<?php 

declare (strict_types=1);

namespace Core\Database;


use Core\Contracts\DatabaseDriver; //pdo konek
use PDO;


final class SQLiteDriver implements DatabaseDriver{
    public function __construct(
        private readonly string $path
        ){}

    public function connect(): PDO {
        $dir = dirname($this->path);
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }



        $pdo = new PDO('sqlite:' . $this->path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}