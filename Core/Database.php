<?php

namespace WiseTrap\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public PDO $pdo;
    public function __construct(array $config)
    {
        $host       = $config['host'] ?? '';
        $dbname     = $config['dbname'] ?? '';
        $port       = $config['port'] ?? '';
        $user       = $config['user'] ?? '';
        $password   = $config['password'] ?? '';
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port;

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Database connection error: ' . $e->getMessage();
            exit();
        }
    }
    public function prepare($sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}