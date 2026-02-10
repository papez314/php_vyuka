<?php

declare(strict_types=1);

/**
 * Lekce 4: Pole (arrays)
 * Spuštění: php lekce/04-pole.php
 */

echo "=== Indexované pole ===\n";
$barvy = ['červená', 'zelená', 'modrá'];
echo "První barva: {$barvy[0]}\n";
echo "Počet barev: " . count($barvy) . "\n";

// Přidání prvku
$barvy[] = 'žlutá';
print_r($barvy);

echo "\n=== Asociativní pole ===\n";
$student = [
    'jmeno' => 'Jan',
    'prijmeni' => 'Novák',
    'vek' => 17,
    'trida' => '2.A',
];

echo "Student: {$student['jmeno']} {$student['prijmeni']}\n";

echo "\n=== Pole polí (2D pole) ===\n";
$produkty = [
    ['nazev' => 'Tričko', 'cena' => 299],
    ['nazev' => 'Kalhoty', 'cena' => 899],
    ['nazev' => 'Boty', 'cena' => 1299],
];

foreach ($produkty as $produkt) {
    echo "{$produkt['nazev']}: {$produkt['cena']} Kč\n";
}

echo "\n=== Spread operátor ===\n";
$cisla1 = [1, 2, 3];
$cisla2 = [4, 5, 6];
$vsechna = [...$cisla1, ...$cisla2];
print_r($vsechna);

echo "\n=== Destrukturalizace ===\n";
$souradnice = [50.08, 14.43];
[$lat, $lng] = $souradnice;
echo "Zeměpisná šířka: {$lat}, délka: {$lng}\n";

// Destrukturalizace asociativního pole
['jmeno' => $jmeno, 'vek' => $vek] = $student;
echo "Jméno: {$jmeno}, věk: {$vek}\n";

echo "\n=== Užitečné funkce ===\n";
$cisla = [5, 2, 8, 1, 9];

echo 'Součet: ' . array_sum($cisla) . "\n";
echo 'Maximum: ' . max($cisla) . "\n";
echo 'Minimum: ' . min($cisla) . "\n";
echo 'Je 8 v poli? ' . (in_array(8, $cisla, true) ? 'ano' : 'ne') . "\n";

sort($cisla);
echo 'Seřazeno: ' . implode(', ', $cisla) . "\n";

echo "\n=== Array map/filter ===\n";
$cisla = [1, 2, 3, 4, 5];

// Zdvojnásob každé číslo
$zdvojnasobena = array_map(fn(int $n): int => $n * 2, $cisla);
echo 'Zdvojnásobená: ' . implode(', ', $zdvojnasobena) . "\n";

// Vyfiltruj sudá čísla
$suda = array_filter($cisla, fn(int $n): bool => $n % 2 === 0);
echo 'Sudá: ' . implode(', ', $suda) . "\n";

echo "\n--- Tvůj úkol ---\n";
// TODO: Vytvoř pole svých 5 oblíbených filmů a vypiš je s pořadím
// TODO: Použij array_filter k vyfiltrování filmů začínajících na určité písmeno
