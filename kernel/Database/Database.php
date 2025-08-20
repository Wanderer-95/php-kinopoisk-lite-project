<?php

namespace Kernel\Database;

use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->connect();
    }

    public function connect(): void
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
            //echo "Подключение успешно!";
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    }
}