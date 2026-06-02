<?php

declare(strict_types=1);
namespace Core\Database;


abstract class Model{
    public function __construct(protected readonly Database $database){}

    abstract protected function table(): string;
    public function all(string $orderBy): array{
        return $this->database->fetchAll(
            sprintf('SELECT * FROM %s ORDER BY %s', $this->table(), $orderBy),
        );
    }

    public function find(int $id): ?array{
        return $this->database->fetchOne(
            sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', $this->table()),
            ['id' => $id],
        );
    }    


    public function insert(array $data): int{
        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table(),
            implode(', ', $columns),
            implode(', ', $placeholders),
        );

        $this->database->query($sql, $data);

        return (int) $this->database->lastInsertId();
    }


    public function update(int $id, array $data): bool{
        $items = [];
        foreach (array_keys($data) as $column) {
            $items[] = "{$column} = :{$column}";
        }

        $data['id'] = $id;

        $sql = sprintf('UPDATE %s SET %s WHERE id = :id',
               $this->table(),implode(', ', $items),);

        return $this->database->query($sql, $data)->rowCount() > 0;
    }

    public function remove(int $id): bool{
        return $this->database->query(
            sprintf('DELETE FROM %s WHERE id = :id', $this->table()),['id' => $id],)->rowCount() > 0;
    }
}