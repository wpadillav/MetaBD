<?php
namespace App;

use PDO;

class SchemaReader {
    private PDO $pdo;
    private string $dbName;

    public function __construct(string|null $dbName = null) {
        $this->pdo = Database::connect();
        $this->dbName = $dbName ?? $_ENV['DB_NAME'];
    }

    /**
     * Devuelve una lista de bases de datos (excluyendo las internas del sistema)
     */
    public function getDatabases(): array {
        $stmt = $this->pdo->query("SHOW DATABASES");
        $all = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_filter($all, fn($db) =>
            !in_array($db, ['information_schema', 'mysql', 'performance_schema', 'sys'])
        );
    }

    /**
     * Devuelve una lista de tablas con su motor y comentario
     */
    public function getTables(): array {
        $stmt = $this->pdo->prepare("
            SELECT TABLE_NAME, ENGINE, TABLE_COMMENT
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = :db
        ");
        $stmt->execute(['db' => $this->dbName]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve detalles de columnas para una tabla dada
     */
    public function getTableDetails(string $table): array {
        $stmt = $this->pdo->prepare("
            SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, EXTRA, COLUMN_COMMENT
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table
        ");
        $stmt->execute(['db' => $this->dbName, 'table' => $table]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve claves foráneas de una tabla
     */
    public function getForeignKeys(string $table): array {
        $stmt = $this->pdo->prepare("
            SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        $stmt->execute(['db' => $this->dbName, 'table' => $table]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve la base de datos actual que está leyendo
     */
    public function getCurrentDatabase(): string {
        return $this->dbName;
    }
}
