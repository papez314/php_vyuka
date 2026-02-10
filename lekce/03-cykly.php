<?php

declare(strict_types=1);

/**
 * Lekce 3: Cykly
 * Spuštění: php lekce/03-cykly.php
 */

echo "=== FOR cyklus ===\n";
for ($i = 1; $i <= 5; $i++) {
    echo "Iterace: {$i}\n";
}

echo "\n=== WHILE cyklus ===\n";
$pocet = 0;
while ($pocet < 3) {
    echo "Počet: {$pocet}\n";
    $pocet++;
}

echo "\n=== DO-WHILE cyklus ===\n";
$x = 0;
do {
    echo "X = {$x}\n";
    $x++;
} while ($x < 3);

echo "\n=== FOREACH - procházení pole ===\n";
$ovoce = ['jablko', 'hruška', 'banán'];

foreach ($ovoce as $kus) {
    echo "- {$kus}\n";
}

echo "\n=== FOREACH s indexem ===\n";
foreach ($ovoce as $index => $kus) {
    $poradi = $index + 1;
    echo "{$poradi}. {$kus}\n";
}

echo "\n=== FOREACH s klíčem (asociativní pole) ===\n";
$ceny = [
    'jablko' => 25,
    'hruška' => 30,
    'banán' => 35,
];

foreach ($ceny as $nazev => $cena) {
    echo "{$nazev}: {$cena} Kč\n";
}

echo "\n=== Break a Continue ===\n";
for ($i = 1; $i <= 10; $i++) {
    if ($i === 3) {
        continue;  // Přeskoč 3
    }
    if ($i === 7) {
        break;     // Ukonči cyklus na 7
    }
    echo "{$i} ";
}
echo "\n";

echo "\n--- Tvůj úkol ---\n";
// TODO: Vypiš čísla od 10 do 1 (odpočítávání)
// TODO: Vypiš malou násobilku čísla 7 (7x1=7, 7x2=14, ...)
