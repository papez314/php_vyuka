<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Výuka</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #4F5B93; }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        code {
            background: #e8e8e8;
            padding: 2px 6px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h1>PHP Výuka</h1>

    <div class="card">
        <h2>Aktuální datum a čas</h2>
        <p><?= date('d.m.Y H:i:s') ?></p>
    </div>

    <div class="card">
        <h2>Informace o PHP</h2>
        <p>Verze PHP: <code><?= PHP_VERSION ?></code></p>
        <p>Operační systém: <code><?= PHP_OS ?></code></p>
    </div>

    <div class="card">
        <h2>Příklady</h2>
        <ul>
            <li><a href="formular.php">Formulář</a></li>
        </ul>
    </div>

    <?php
    // PHP kód můžeš psát přímo do HTML
    $pozdrav = "Vítej v PHP!";
    ?>

    <div class="card">
        <h2><?= $pozdrav ?></h2>
        <p>Tento text je generovaný PHP.</p>
    </div>
</body>
</html>
