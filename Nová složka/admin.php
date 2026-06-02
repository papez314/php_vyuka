<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }

$dbPath = __DIR__ . '/spolek.db';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Neplatný token.');
    }

    try {
        $pdo = new PDO("sqlite:" . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['add_news'])) {
            $stmt = $pdo->prepare("INSERT INTO news (title, category_tag, published_date, content) VALUES (?, ?, ?, ?)");
            $stmt->execute([trim($_POST['title']), trim($_POST['category_tag']), trim($_POST['published_date']), trim($_POST['content'])]);
            header('Location: index.php');
            exit;
        }

        if (isset($_POST['add_event'])) {
            $stmt = $pdo->prepare("INSERT INTO events (event_date, title) VALUES (?, ?)");
            $stmt->execute([trim($_POST['event_date']), trim($_POST['event_title'])]);
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) { $message = "Chyba: " . $e->getMessage(); }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Samostatná Administrace</title>
    <link rel="stylesheet" href="projekt2.css">
    <style>
        .admin-wrap { max-width: 600px; margin: 30px auto; padding: 20px; background: #fff; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 4px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { background: #2c5e3b; color: white; padding: 10px 15px; border: none; cursor: pointer; font-weight: bold; width: 100%; }
        .btn-back { display: inline-block; background: #555; color: white; padding: 8px 12px; text-decoration: none; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-wrap">
        <a href="index.php" class="btn-back">← Zpět na hlavní web</a>
        
        <h1>Administrační rozhraní</h1>
        <?php if($message): ?><p style="color:red;"><?= $message ?></p><?php endif; ?>
        
        <hr>
        <h2>Přidat aktualitu</h2>
        <form action="admin.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group"><label>Nadpis:</label><input type="text" name="title" required></div>
            <div class="form-group"><label>Kategorie:</label><input type="text" name="category_tag" value="Aktuality"></div>
            <div class="form-group"><label>Datum:</label><input type="text" name="published_date" value="<?= date('j. n. Y') ?>"></div>
            <div class="form-group"><label>Obsah:</label><textarea name="content" rows="4" required></textarea></div>
            <button type="submit" name="add_news" class="btn">Publikovat aktualitu</button>
        </form>

        <hr>
        <h2>Přidat akci do kalendáře</h2>
        <form action="admin.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group"><label>Datum akce (např. 24. 6.):</label><input type="text" name="event_date" placeholder="15. 1." required></div>
            <div class="form-group"><label>Název akce:</label><input type="text" name="event_title" placeholder="Společný hon" required></div>
            <button type="submit" name="add_event" class="btn">Přidat do kalendáře</button>
        </form>
    </div>
</body>
</html>