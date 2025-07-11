<?php

use App\SchemaReader;
use App\TableMetadata;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$table = $_GET['table'] ?? '';
$db = $_GET['db'] ?? $_ENV['DB_NAME'];

$reader = new SchemaReader($db);
$metadata = new TableMetadata($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metadata->save($table, $_POST['what'], $_POST['where']);
}

$columns = $reader->getTableDetails($table);
$fks = $reader->getForeignKeys($table);
$desc = $metadata->get($table);

ob_start();
?>
<h2>Base de datos: <?= htmlspecialchars($db) ?></h2>
<h3><strong>Tabla: </strong><?= htmlspecialchars($table) ?></h3>

<form method="POST" action="?db=<?= urlencode($db) ?>&table=<?= urlencode($table) ?>">
    <label>¿Qué guarda esta tabla?</label><br>
    <textarea name="what"><?= htmlspecialchars($desc['what']) ?></textarea><br>
    
    <label>¿Dónde se usa esta tabla?</label><br>
    <textarea name="where"><?= htmlspecialchars($desc['where']) ?></textarea><br>

    <button type="submit">Guardar</button>
</form>

<h3>Columnas</h3>
<table>
    <tr><th>Nombre</th><th>Tipo</th><th>Nullable</th><th>Clave</th><th>Extra</th><th>Comentario</th></tr>
    <?php foreach ($columns as $col): ?>
    <tr>
        <td><?= htmlspecialchars($col['COLUMN_NAME']) ?></td>
        <td><?= htmlspecialchars($col['COLUMN_TYPE']) ?></td>
        <td><?= htmlspecialchars($col['IS_NULLABLE']) ?></td>
        <td><?= htmlspecialchars($col['COLUMN_KEY']) ?></td>
        <td><?= htmlspecialchars($col['EXTRA']) ?></td>
        <td><?= htmlspecialchars($col['COLUMN_COMMENT']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php if (count($fks) > 0): ?>
<h3>Claves Foráneas</h3>
<ul>
    <?php foreach ($fks as $fk): ?>
        <li><?= htmlspecialchars($fk['COLUMN_NAME']) ?> → <?= htmlspecialchars($fk['REFERENCED_TABLE_NAME']) ?>.<?= htmlspecialchars($fk['REFERENCED_COLUMN_NAME']) ?></li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <p><em>No hay claves foráneas definidas.</em></p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
