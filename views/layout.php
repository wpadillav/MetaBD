<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inspector de Base de Datos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        textarea { width: 100%; height: 80px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <header>
        <h1>Inspector de Base de Datos</h1>
        <nav>
            <a href="index.php">Inicio</a>
        </nav>
        <hr>
    </header>

    <main>
        <?php if (isset($content)) echo $content; ?>
    </main>

    <footer>
        <hr>
        <p style="text-align:center;">&copy; <?= date('Y') . " " . $_ENV['DB_DESARROLLO'] ?></p>
    </footer>

</body>
</html>
