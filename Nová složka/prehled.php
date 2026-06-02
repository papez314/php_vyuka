<?php
declare(strict_types=1);
$dbPath = __DIR__ . '/spolek.db';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Načtení aktualit (nejnovější jako první)
    $newsQuery = $pdo->query("SELECT * FROM news ORDER BY id DESC");
    $newsList = $newsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Načtení akcí
    $eventsQuery = $pdo->query("SELECT * FROM events ORDER BY id ASC");
    $eventsList = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Chyba při načítání dat: " . $e->getMessage());
}
?>
<?php foreach ($newsList as $news): ?>
    <article class="post">
        <span class="category-tag"><?= htmlspecialchars($news['category_tag']) ?></span>
        <h2><?= htmlspecialchars($news['title']) ?></h2>
        <p class="post-date">Publikováno: <?= htmlspecialchars($news['published_date']) ?></p>
        <p><?= htmlspecialchars($news['content']) ?></p>
    </article>
<?php endforeach; ?>

<ul class="event-list">
    <?php foreach ($eventsList as $event): ?>
        <li><strong><?= htmlspecialchars($event['event_date']) ?></strong> - <?= htmlspecialchars($event['title']) ?></li>
    <?php endforeach; ?>
</ul>