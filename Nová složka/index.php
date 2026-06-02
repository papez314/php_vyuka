<?php
declare(strict_types=1);
$dbPath = __DIR__ . '/spolek.db';
$newsList = [];
$eventsList = [];

if (file_exists($dbPath)) {
    try {
        $pdo = new PDO("sqlite:" . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $newsList = $pdo->query("SELECT * FROM news ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $eventsList = $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {}
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Myslivecký spolek Poličná</title>
    <link rel="stylesheet" href="projekt2.css">
</head>
<body>

    <header class="main-header">
        <h1><a href="index.php">Myslivecký spolek Branky - Poličná</a></h1>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="Aktuality.html">Aktuality</a></li>
            <li><a href="O_nas.html">O nás</a></li>
            <li><a href="plan_akci.html">Plán akcí</a></li>
            <li><a href="fotogalerie.html">Fotogalerie</a></li>
            <li><a href="seznam_clenu.html">Seznam členů</a></li>
            <li><a href="kontaktni_udaje.html">Kontaktní údaje</a></li>
            <li><a href="admin.php" style="background: #1e3f28; color: #fff; border-radius: 4px; padding: 5px 10px; font-weight: bold;">⚙️ Administrace</a></li>
        </ul>
    </nav>

    <div class="container">
        <main class="content">
            <?php if (!empty($newsList)): ?>
                <?php foreach ($newsList as $news): ?>
                    <article class="post">
                        <span class="category-tag"><?= htmlspecialchars($news['category_tag']) ?></span>
                        <h2><?= htmlspecialchars($news['title']) ?></h2>
                        <p class="post-date"><?= htmlspecialchars($news['published_date']) ?></p>
                        <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Žádné aktuality k zobrazení.</p>
            <?php endif; ?>
        </main>

        <aside class="sidebar">
            <section class="sidebar-box">
                <h3>Kalendář akcí</h3>
                <ul class="event-list">
                    <?php if (!empty($eventsList)): ?>
                        <?php foreach ($eventsList as $event): ?>
                            <li><strong><?= htmlspecialchars($event['event_date']) ?></strong> - <?= htmlspecialchars($event['title']) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Žádné plánované akce</li>
                    <?php endif; ?>
                </ul>
            </section>
        </aside>
    </div>
</body>
</html>