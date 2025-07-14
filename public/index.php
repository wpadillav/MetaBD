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

<h2>Seleccionar Base de Datos</h2>
<form method="get" action="index.php">
    <select name="db" onchange="this.form.submit()">
        <?php foreach ($databases as $db): ?>
            <option value="<?= $db ?>" <?= $db === $selectedDb ? 'selected' : '' ?>>
                <?= $db ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<h3>Base seleccionada: <?= $selectedDb ?></h3>
<p>Total de tablas: <?= count($tables) ?></p>
<ul>
    <?php foreach ($tables as $table): ?>
        <li>
            <strong><?= $table['TABLE_NAME'] ?></strong> - Motor: <?= $table['ENGINE'] ?><br>
            Comentario DB: <?= $table['TABLE_COMMENT'] ?><br>
            Descripción (usuario): <?= $metadata->get($table['TABLE_NAME'])['what'] ?? '' ?><br>
<a href="table_detail.php?db=<?= $selectedDb ?>&table=<?= $table['TABLE_NAME'] ?>">Ver tabla</a>

            <!-- <a href="table_detail.php?db=<?= $selectedDb ?>&table=<?= $table['TABLE_NAME'] ?>">Ver tabla</a> -->
        </li>
    <?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
