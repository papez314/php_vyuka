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
    <style>
        /* CSS to ensure custom search form fits nicely */
        .search-form {
            display: flex;
            width: 100%;
        }
        .search-field {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #fcfdfa;
        }
        .search-field:focus {
            outline: none;
            border-color: #2e3d23;
            background-color: #fff;
        }
        /* Style for read-more links or tags */
        .post .category-tag.secondary {
            background: #a3c18c;
            color: #2e3d23;
        }
        .side-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .side-menu li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .side-menu li a {
            color: #2e3d23;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .side-menu li a:hover {
            color: #a3c18c;
        }
        .search-reset {
            margin-top: 10px;
            font-size: 0.85rem;
            display: inline-block;
            color: #888;
            text-decoration: none;
        }
        .search-reset:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header class="main-header">
        <h1><a href="index.php">Myslivecký spolek Branky - Poličná</a></h1>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Aktuality</a></li>
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