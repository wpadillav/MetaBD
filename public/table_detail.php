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

$allTables = $reader->getTables(); // Para el selector de tablas

if (!$table) {
    echo "<div class='container my-5'><div class='alert alert-warning'>⚠️ Selecciona una tabla para continuar.</div></div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metadata->save($table, $_POST['what'], $_POST['where']);
}

$columns = $reader->getTableDetails($table);
$fks = $reader->getForeignKeys($table);
$desc = $metadata->get($table);

ob_start();
?>

<div class="container my-4">

    <h2 class="mb-3">📁 Base de datos: <span class="text-primary"><?= htmlspecialchars($db) ?></span></h2>

    <!-- Selector de tabla -->
    <form method="get" action="" class="row g-2 align-items-center mb-4">
        <input type="hidden" name="db" value="<?= htmlspecialchars($db) ?>">
        <div class="col-auto">
            <label for="table-select" class="form-label">Seleccionar tabla:</label>
        </div>
        <div class="col-auto">
            <select id="table-select" name="table" class="form-select" onchange="this.form.submit()">
                <option value="">-- Elegir tabla --</option>
                <?php foreach ($allTables as $tbl): ?>
                    <option value="<?= htmlspecialchars($tbl['TABLE_NAME']) ?>" <?= $tbl['TABLE_NAME'] === $table ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tbl['TABLE_NAME']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <h3 class="mb-4">📄 <strong>Tabla:</strong> <?= htmlspecialchars($table) ?></h3>

    <!-- Formulario para descripción -->
    <form method="POST" action="?db=<?= urlencode($db) ?>&table=<?= urlencode($table) ?>" class="mb-5">
        <div class="mb-3">
            <label for="what" class="form-label">¿Qué guarda esta tabla?</label>
            <textarea id="what" name="what" class="form-control" rows="3"><?= htmlspecialchars($desc['what'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="where" class="form-label">¿Dónde se usa esta tabla?</label>
            <textarea id="where" name="where" class="form-control" rows="3"><?= htmlspecialchars($desc['where'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 Guardar</button>
    </form>

    <!-- Columnas -->
    <h3 class="mb-3">📋 Columnas</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Nullable</th>
                    <th>Clave</th>
                    <th>Extra</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>

    <!-- Claves foráneas -->
    <h3 class="mt-5">🔗 Claves Foráneas</h3>
    <?php if (count($fks) > 0): ?>
        <ul class="list-group">
            <?php foreach ($fks as $fk): ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($fk['COLUMN_NAME']) ?>
                    <span class="text-muted">→</span>
                    <strong><?= htmlspecialchars($fk['REFERENCED_TABLE_NAME']) ?>.<?= htmlspecialchars($fk['REFERENCED_COLUMN_NAME']) ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted"><em>No hay claves foráneas definidas.</em></p>
    <?php endif; ?>

</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
?>
