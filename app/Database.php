<?php
namespace App;

use PDO;
use Dotenv\Dotenv;

class Database {
    private static $pdo;

    public static function connect(): PDO {
        if (!self::$pdo) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();

            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};port={$_ENV['DB_PORT']};charset=utf8mb4";
            self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
