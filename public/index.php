<?php

use App\SchemaReader;
use App\TableMetadata;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = $_GET['db'] ?? $_ENV['DB_NAME'];

$reader = new SchemaReader($db);
$metadata = new TableMetadata($db);

$databases = $reader->getDatabases();
$selectedDb = $_GET['db'] ?? $_ENV['DB_NAME'];

$tables = $reader->getTables();

ob_start();
?>

<div class="container my-4">
    <h1 class="mb-3">📊 Informe General</h1>
    <p class="lead">Total de tablas: <span class="badge bg-secondary"><?= count($tables) ?></span></p>

    <div class="mb-4">
        <h4>Seleccionar Base de Datos</h4>
        <form method="get" action="index.php" class="row g-2">
            <div class="col-auto">
                <select name="db" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($databases as $db): ?>
                        <option value="<?= $db ?>" <?= $db === $selectedDb ? 'selected' : '' ?>>
                            <?= $db ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <div class="list-group">
        <?php foreach ($tables as $table): ?>
            <div class="list-group-item">
                <h5 class="mb-1"><?= $table['TABLE_NAME'] ?> <small class="text-muted">- Motor: <?= $table['ENGINE'] ?></small></h5>
                <p class="mb-1">
                    <strong>Comentario DB:</strong> <?= $table['TABLE_COMMENT'] ?: '<em>Sin comentario</em>' ?><br>
                    <strong>Descripción (usuario):</strong> <?= $metadata->get($table['TABLE_NAME'])['what'] ?? '<em>No disponible</em>' ?>
                </p>
                <a href="table_detail.php?db=<?= $selectedDb ?>&table=<?= $table['TABLE_NAME'] ?>" class="btn btn-sm btn-outline-primary">Ver tabla</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
