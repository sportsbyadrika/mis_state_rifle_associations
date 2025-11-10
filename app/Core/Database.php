<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../bootstrap.php';
            $db = $config['database'];

            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
                $db['connection'],
                $db['host'],
                $db['port'],
                $db['database'],
                $db['charset']
            );

            try {
                self::$instance = new PDO($dsn, $db['username'], $db['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $exception) {
                throw new \RuntimeException('Database connection failed: ' . $exception->getMessage());
            }
        }

        return self::$instance;
    }
}
