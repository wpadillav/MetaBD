<?php
ob_start();
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
<h1>Informe General</h1>
<p>Total de tablas: <?= count($tables) ?></p>
<ul>
    <?php foreach ($tables as $table): ?>
        <li>
            <strong><?= $table['TABLE_NAME'] ?></strong> - Motor: <?= $table['ENGINE'] ?> <br>
            Comentario DB: <?= $table['TABLE_COMMENT'] ?> <br>
            Descripción (usuario): <?= $metadata->get($table['TABLE_NAME'])['what'] ?>
            [<a href="table_detail.php?table=<?= $table['TABLE_NAME'] ?>">Ver</a>]
        </li>
    <?php endforeach; ?>
</ul>