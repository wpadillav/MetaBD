<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MetaBD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header class="bg-primary text-white py-3 mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 m-0">MetaBD</h1>
                <a href="index.php" class="btn btn-light">Inicio</a>
            </div>
        </div>
    </header>

    <main class="container">
        <?php if (isset($content)) echo $content; ?>
    </main>

    <footer class="bg-light text-center text-muted py-3 mt-4 border-top">
        &copy; <?= date('Y') . " " . $_ENV['DB_DESARROLLO'] ?>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
