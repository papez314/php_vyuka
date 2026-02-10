<?php

declare(strict_types=1);

/**
 * Lekce 5: Funkce
 * Spuštění: php lekce/05-funkce.php
 *
 * Poznámka: V PHP definujeme typy u parametrů funkcí a návratových hodnot.
 * Díky declare(strict_types=1) na začátku souboru PHP striktně kontroluje typy za běhu.
 */

echo "=== Jednoduchá funkce ===\n";

function pozdrav(): void
{
    echo "Ahoj světe!\n";
}

pozdrav();


echo "\n=== Funkce s parametrem a typem ===\n";

function pozdravJmenem(string $jmeno): void
{
    echo "Ahoj, {$jmeno}!\n";
}

pozdravJmenem('Petře');
pozdravJmenem('Anno');


echo "\n=== Funkce s návratovou hodnotou ===\n";

function secti(int $a, int $b): int
{
    return $a + $b;
}

$vysledek = secti(5, 3);
echo "5 + 3 = {$vysledek}\n";


echo "\n=== Funkce s výchozí hodnotou parametru ===\n";

function mocnina(int $zaklad, int $exponent = 2): int
{
    return $zaklad ** $exponent;
}

echo '3^2 = ' . mocnina(3) . "\n";
echo '2^8 = ' . mocnina(2, 8) . "\n";


echo "\n=== Pojmenované argumenty (PHP 8.0+) ===\n";

function vytvorUzivatele(string $jmeno, int $vek, bool $aktivni = true): string
{
    $stav = $aktivni ? 'aktivní' : 'neaktivní';
    return "{$jmeno} ({$vek} let) - {$stav}";
}

// Klasické volání
echo vytvorUzivatele('Jan', 25, false) . "\n";

// S pojmenovanými argumenty - pořadí nezáleží
echo vytvorUzivatele(vek: 30, jmeno: 'Petr') . "\n";


echo "\n=== Arrow funkce (krátký zápis) ===\n";

// Klasická anonymní funkce
$nasobDva = function (int $n): int {
    return $n * 2;
};

// Arrow funkce - kratší zápis pro jednoduché funkce
$nasobTri = fn(int $n): int => $n * 3;

echo '5 * 2 = ' . $nasobDva(5) . "\n";
echo '5 * 3 = ' . $nasobTri(5) . "\n";


echo "\n=== Nullable typy ===\n";

function najdiUzivatele(int $id): ?string  // ?string = string nebo null
{
    $uzivatele = [1 => 'Jan', 2 => 'Petr'];
    return $uzivatele[$id] ?? null;
}

echo 'ID 1: ' . (najdiUzivatele(1) ?? 'nenalezen') . "\n";
echo 'ID 99: ' . (najdiUzivatele(99) ?? 'nenalezen') . "\n";


echo "\n=== Union typy (PHP 8.0+) ===\n";

function formatujHodnotu(int|float|string $hodnota): string
{
    return "Hodnota: {$hodnota}";
}

echo formatujHodnotu(42) . "\n";
echo formatujHodnotu(3.14) . "\n";
echo formatujHodnotu('text') . "\n";


echo "\n=== Praktický příklad ===\n";

function vypoctiCenuSDph(float $cena, float $sazba = 21.0): float
{
    return round($cena * (1 + $sazba / 100), 2);
}

$cenaBezDph = 1000.0;
$cenaSdph = vypoctiCenuSDph($cenaBezDph);
echo "Cena bez DPH: {$cenaBezDph} Kč\n";
echo "Cena s DPH: {$cenaSdph} Kč\n";


echo "\n--- Tvůj úkol ---\n";
// TODO: Napiš funkci obdelnik(float $a, float $b): array
//       která vrátí pole s klíči 'obvod' a 'obsah'
