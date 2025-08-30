<?php

namespace Kernel\Database;

use InvalidArgumentException;
use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $driver = config('db.driver');
        $host = config('db.host');
        $db = config('db.database');
        $user = config('db.user');
        $pass = config('db.pass');
        $charset = config('db.charset');

        $dsn = "$driver:host=$host;dbname=$db;charset=$charset";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            // echo "Подключение успешно!";
        } catch (PDOException $e) {
            echo 'Ошибка: '.$e->getMessage();
        }
    }

    public function insert(string $table, array $data): int|false
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $binds = implode(', ', array_map(fn ($v) => ":$v", $keys));
        $sql = "INSERT INTO $table ($fields) VALUES ($binds)";
        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($data);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function first(string $table, array $conditions = []): ?array
    {
        $where = '';
        if (! empty($conditions)) {
            $binds = array_map(fn ($v) => "$v = :$v", array_keys($conditions));
            $where = 'WHERE '.implode(' AND ', $binds);
        }

        $sql = "SELECT * FROM {$table} {$where} LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($conditions);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function get(string $table, array $conditions = [], ?int $limit = null, ?string $order = null): array
    {
        $sql = "SELECT * FROM {$table}";
        $params = [];

        // WHERE
        if (!empty($conditions)) {
            $wheres = [];
            foreach ($conditions as $field => $value) {
                $wheres[] = "{$field} = :{$field}";
                $params[$field] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $wheres);
        }

        // ORDER BY
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }

        // LIMIT
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete(string $table, array $conditions = []): void
    {
        // Начинаем с базового запроса
        $sql = "DELETE FROM {$table}";
        $params = [];

        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $column => $value) {
                $clauses[] = "{$column} = :{$column}";
                $params[":{$column}"] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update(string $table, array $data, array $conditions = []): ?array
    {
        if (empty($data)) {
            throw new InvalidArgumentException("Update data cannot be empty");
        }

        // Формируем SET часть
        $setClauses = [];
        $params = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "{$column} = :set_{$column}";
            $params[":set_{$column}"] = $value;
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $setClauses);

        // Формируем WHERE часть
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "{$column} = :where_{$column}";
                $params[":where_{$column}"] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (PDOException $e) {
            throw new RuntimeException("Update failed: " . $e->getMessage());
        }

        // Если ничего не обновилось → возвращаем null
        if ($stmt->rowCount() === 0) {
            return null;
        }

        // Достаём обновлённую запись
        if (!empty($conditions)) {
            $whereClauses = [];
            $whereParams = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "{$column} = :{$column}";
                $whereParams[":{$column}"] = $value;
            }
            $sql = "SELECT * FROM {$table} WHERE " . implode(' AND ', $whereClauses) . " LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($whereParams);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        }

        return null;
    }
}
