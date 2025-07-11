<?php
namespace App;

class TableMetadata {
    private string $file;

    public function __construct(?string $dbName = null) {
        $base = $dbName ?? $_ENV['DB_NAME'];  // Usa el nombre de la base actual o la del .env por defecto
        $safeDb = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base);  // Limpieza de nombre para archivo seguro
        $this->file = __DIR__ . '/../storage/descriptions_' . $safeDb . '.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        } else {
            $contents = file_get_contents($this->file);
            if (empty($contents) || json_decode($contents, true) === null) {
                file_put_contents($this->file, json_encode([]));
            }
        }
    }

    public function getAll(): array {
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    public function get(string $table): array {
        $data = $this->getAll();
        return $data[$table] ?? ['what' => '', 'where' => ''];
    }

    public function save(string $table, string $what, string $where): void {
        $data = $this->getAll();
        $data[$table] = ['what' => $what, 'where' => $where];
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
