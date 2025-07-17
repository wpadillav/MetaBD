<?php

use App\SchemaReader;
use App\TableMetadata;

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Parámetros
$selectedDb = $_GET['db'] ?? $_ENV['DB_NAME'];
$searchTerm = $_GET['search'] ?? '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;

$reader = new SchemaReader($selectedDb);
$metadata = new TableMetadata($selectedDb);

$databases = $reader->getDatabases();
$tables = $reader->getTables();

// Filtro por búsqueda
if (!empty($searchTerm)) {
    $tables = array_filter($tables, function ($table) use ($searchTerm) {
        return stripos($table['TABLE_NAME'], $searchTerm) !== false;
    });
}

// Paginación
$totalTables = count($tables);
$totalPages = max(1, ceil($totalTables / $perPage));
$start = ($page - 1) * $perPage;
$tablesPaginated = array_slice($tables, $start, $perPage);

ob_start();
?>

<div class="container my-4">
    <h1 class="mb-3">📊 Informe General</h1>
    <p class="lead">Total de tablas: <span class="badge bg-secondary"><?= $totalTables ?></span></p>

    <!-- Selección de base de datos -->
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

    <!-- Filtro de búsqueda -->
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="db" value="<?= htmlspecialchars($selectedDb) ?>">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Buscar tabla..." value="<?= htmlspecialchars($searchTerm) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <!-- Tabla de resultados -->
    <div class="list-group">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tabla</th>
                    <th>Motor</th>
                    <th>Comentario DB</th>
                    <th>Descripción (usuario)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tablesPaginated as $index => $table): ?>
                    <tr>
                        <th scope="row"><?= $start + $index + 1 ?></th>
                        <td><?= htmlspecialchars($table['TABLE_NAME']) ?></td>
                        <td><?= htmlspecialchars($table['ENGINE']) ?></td>
                        <td><?= $table['TABLE_COMMENT'] ?: '<em>Sin comentario</em>' ?></td>
                        <td><?= $metadata->get($table['TABLE_NAME'])['what'] ?? '<em>No disponible</em>' ?></td>
                        <td>
                            <a href="table_detail.php?db=<?= urlencode($selectedDb) ?>&table=<?= urlencode($table['TABLE_NAME']) ?>" class="btn btn-sm btn-outline-primary">Ver tabla</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Paginación">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?db=<?= urlencode($selectedDb) ?>&search=<?= urlencode($searchTerm) ?>&page=<?= $page - 1 ?>">&laquo;</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?db=<?= urlencode($selectedDb) ?>&search=<?= urlencode($searchTerm) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?db=<?= urlencode($selectedDb) ?>&search=<?= urlencode($searchTerm) ?>&page=<?= $page + 1 ?>">&raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
