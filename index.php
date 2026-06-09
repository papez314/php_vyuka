<?php
declare(strict_types=1);
$dbPath = __DIR__ . '/spolek.db';
$newsList = [];
$eventsList = [];
$searchQuery = trim($_GET['search'] ?? '');

if (file_exists($dbPath)) {
    try {
        $pdo = new PDO("sqlite:" . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Fetch news, with optional search filtering
        if ($searchQuery !== '') {
            $stmt = $pdo->prepare("SELECT * FROM news WHERE title LIKE ? OR content LIKE ? OR category_tag LIKE ? ORDER BY id DESC");
            $likeQuery = '%' . $searchQuery . '%';
            $stmt->execute([$likeQuery, $likeQuery, $likeQuery]);
            $newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $newsList = $pdo->query("SELECT * FROM news ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Fetch calendar events
        $eventsList = $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        // Combine news items as calendar events
        $calendarEvents = [];
        foreach ($newsList as $newsItem) {
            $calendarEvents[] = ['event_date' => $newsItem['published_date'], 'title' => $newsItem['title']];
        }
        foreach ($eventsList as $event) {
            $calendarEvents[] = $event;
        }
    } catch (PDOException $e) {
        // Fallback if db fails
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myslivecký spolek Poličná</title>
    <link rel="stylesheet" href="projekt2.css">

</head>
<body>

    <header class="main-header">
        <h1><a href="index.php">Myslivecký spolek Branky - Poličná</a></h1>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Aktuality</a></li>
            <li><a href="O_nas.html">O nás</a></li>
            <li><a href="plan_akci.php">Plán akcí</a></li>
            <li><a href="fotogalerie.html">Fotogalerie</a></li>
            <li><a href="seznam_clenu.html">Seznam členů</a></li>
            <li><a href="kontaktni_udaje.html">Kontaktní údaje</a></li>
            <li><a href="admin.php" class="u-admin-link">⚙️ Administrace</a></li>
        </ul>
    </nav>

    <div class="container">
        <main class="content">
            <?php if ($searchQuery !== ''): ?>
                <div style="margin-bottom: 20px;">
                    <h3>Výsledky vyhledávání pro: "<?= htmlspecialchars($searchQuery) ?>"</h3>
                    <a href="index.php" class="search-reset">← Zobrazit všechny aktuality</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($newsList)): ?>
                <?php foreach ($newsList as $news): ?>
                    <article class="post">
                        <span class="category-tag <?= $news['category_tag'] !== 'Aktuality' ? 'secondary' : '' ?>">
                            <?= htmlspecialchars($news['category_tag']) ?>
                        </span>
                        <h2><?= htmlspecialchars($news['title']) ?></h2>
                        <p class="post-date"><?= htmlspecialchars($news['published_date']) ?></p>
                        <p><?= nl2br(htmlspecialchars($news['content'])) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="post">
                    <p>Nebyly nalezeny žádné aktuality.</p>
                </div>
            <?php endif; ?>
        </main>

        <aside class="sidebar">
            <section class="sidebar-box white-box">
                <h3>Hledat</h3>
                <form action="index.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Hledat aktuality..." class="search-field" value="<?= htmlspecialchars($searchQuery) ?>">
                </form>
                <?php if ($searchQuery !== ''): ?>
                    <a href="index.php" class="search-reset">← Zrušit vyhledávání</a>
                <?php endif; ?>
            </section>

            

            <section class="sidebar-box white-box">
                <h3>Základní informace</h3>
                <ul class="side-menu">
                    <li><a href="doby_lovu.html">Doby lovu</a></li>
                    <li><a href="popis_honitby.html">Popis honitby</a></li>
                    <li><a href="mapa.html">Mapa</a></li>
                    <li><a href="historie.html">Historie</a></li>
                    <li><a href="popis_hranic.html">Popis hranic</a></li>
                    <li><a href="cinnost_spolku.html">Činnost spolku</a></li>
                </ul>
            </section>
        </aside>
    </div>

    <footer class="main-footer">
        <p>© 2026 Myslivecký spolek Branky - Poličná | Lesu a lovu zdar!</p>
    </footer>

</body>
</html>