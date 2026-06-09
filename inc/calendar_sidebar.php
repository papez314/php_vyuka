<?php
// inc/calendar_sidebar.php
// This file outputs a list of calendar events merged from news and events tables.
// Intended for inclusion in sidebars across the site.

$dbPath = __DIR__ . '/../spolek.db';
$calendarEvents = [];
if (file_exists($dbPath)) {
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // News as events
        $newsStmt = $pdo->query('SELECT published_date AS event_date, title FROM news');
        foreach ($newsStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $calendarEvents[] = ['event_date' => $row['event_date'], 'title' => $row['title']];
        }
        // Regular events
        $eventsStmt = $pdo->query('SELECT event_date, title FROM events');
        foreach ($eventsStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $calendarEvents[] = ['event_date' => $row['event_date'], 'title' => $row['title']];
        }
        // Sort by date
        usort($calendarEvents, fn($a, $b) => strcmp($a['event_date'], $b['event_date']));
    } catch (PDOException $e) {
        // ignore errors, keep empty list
    }
}
?>
<div class="sidebar-box white-box">
    <h3>Kalendář akcí</h3>
    <ul class="event-list">
        <?php if (!empty($calendarEvents)): ?>
            <?php foreach ($calendarEvents as $event): ?>
                <li><strong><?= htmlspecialchars($event['event_date']) ?></strong> – <?= htmlspecialchars($event['title']) ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Žádné plánované akce</li>
        <?php endif; ?>
    </ul>
</div>
