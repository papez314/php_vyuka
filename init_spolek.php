<?php
declare(strict_types=1);

$dbPath = __DIR__ . '/spolek.db';

// Pokud databáze existuje, smaže se a vytvoří znovu (reset)
if (file_exists($dbPath)) {
    unlink($dbPath);
}

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabulka pro aktuality
    $pdo->exec("CREATE TABLE IF NOT EXISTS news (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        category_tag TEXT NOT NULL,
        published_date TEXT NOT NULL,
        content TEXT NOT NULL
    )");

    // Tabulka pro kalendář akcí v bočním panelu
    $pdo->exec("CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        event_date TEXT NOT NULL,
        title TEXT NOT NULL
    )");

    // Tabulka pro administrátory
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )");

    echo "Databáze a tabulky byly úspěšně vytvořeny.<br>";

    // Vložení původních aktualit z vašich HTML stránek
    $stmtNews = $pdo->prepare("INSERT INTO news (title, category_tag, published_date, content) VALUES (?, ?, ?, ?)");
    $stmtNews->execute([
        'Pozvánka na výroční členskou schůzi',
        'Aktuality',
        '6. ledna 2026',
        'Zveme všechny členy na výroční členskou schůzi, která se koná příští pátek v obecním domě. Na programu je zhodnocení uplynulé sezóny a plán přikrmování na zimu.'
    ]);
    $stmtNews->execute([
        'Úspěšné sčítání zvěře',
        'Oznámení',
        '2. ledna 2026',
        'V posledních dnech proběhlo v naší honitbě pravidelné sčítání zvěře. I přes nepříznivé počasí se podařilo zmapovat stavy srnčí a černé zvěře.'
    ]);

    // Vložení původních akcí z vašeho kalendáře v index.html
    $stmtEvent = $pdo->prepare("INSERT INTO events (event_date, title) VALUES (?, ?)");
    $stmtEvent->execute(['15. 1.', 'Odchyt zajíců']);
    $stmtEvent->execute(['24. 1.', 'Společný hon']);
    $stmtEvent->execute(['14. 2.', 'Myslivecký ples']);

    // Vložení výchozího administrátora
    $stmtUser = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmtUser->execute([
        'myslivec',
        password_hash('Les123', PASSWORD_DEFAULT)
    ]);

    echo "Výchozí data a administrátorský účet (myslivec) byly úspěšně importovány.";

} catch (PDOException $e) {
    die("Chyba při vytváření databáze: " . $e->getMessage());
}