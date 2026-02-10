<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulář - PHP Výuka</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 600px;
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
        label { display: block; margin: 10px 0 5px; }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #4F5B93;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #3d4875; }
        .result {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
        a { color: #4F5B93; }
    </style>
</head>
<body>
    <h1>Formulář</h1>
    <p><a href="index.php">← Zpět</a></p>

    <div class="card">
        <h2>Registrace</h2>

        <form method="POST" action="">
            <label for="jmeno">Jméno:</label>
            <input type="text" id="jmeno" name="jmeno" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="vek">Věk:</label>
            <input type="number" id="vek" name="vek" min="1" max="120">

            <label for="trida">Třída:</label>
            <select id="trida" name="trida">
                <option value="1.A">1.A</option>
                <option value="2.A">2.A</option>
                <option value="3.A">3.A</option>
                <option value="4.A">4.A</option>
            </select>

            <button type="submit">Odeslat</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="result">
                <h3>Odeslaná data:</h3>
                <?php
                // Nikdy nevěř uživatelskému vstupu - vždy escapuj!
                $jmeno = htmlspecialchars($_POST['jmeno'] ?? '');
                $email = htmlspecialchars($_POST['email'] ?? '');
                $vek = (int)($_POST['vek'] ?? 0);
                $trida = htmlspecialchars($_POST['trida'] ?? '');
                ?>
                <p><strong>Jméno:</strong> <?= $jmeno ?></p>
                <p><strong>Email:</strong> <?= $email ?></p>
                <p><strong>Věk:</strong> <?= $vek ?></p>
                <p><strong>Třída:</strong> <?= $trida ?></p>

                <h4>Raw data ($_POST):</h4>
                <pre><?php print_r($_POST); ?></pre>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
