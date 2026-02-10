<?php

declare(strict_types=1);

/**
 * Lekce 1: Proměnné a datové typy
 * Spuštění: php lekce/01-promenne.php
 *
 * Poznámka: PHP nepodporuje typování lokálních proměnných (ani v PHP 8.4).
 * Typy lze definovat pouze u parametrů funkcí, návratových hodnot a vlastností tříd.
 * Typ proměnné se určuje automaticky podle přiřazené hodnoty.
 */

// Proměnné začínají znakem $
$jmeno = 'Jan';           // string - textový řetězec
$vek = 17;                // int - celé číslo
$vyska = 1.75;            // float - desetinné číslo
$jeStudent = true;        // bool - pravda/nepravda

// Výpis na obrazovku
echo "Jméno: {$jmeno}\n";
echo "Věk: {$vek}\n";
echo "Výška: {$vyska} m\n";
echo 'Je student: ' . ($jeStudent ? 'ano' : 'ne') . "\n";

// Konstanty - hodnoty, které se nemění
const SKOLNI_ROK = '2025/2026';
echo 'Školní rok: ' . SKOLNI_ROK . "\n";

// Null - speciální hodnota "nic"
$prezdivka = null;
echo 'Přezdívka: ' . ($prezdivka ?? 'nemá') . "\n";  // ?? je null coalescing operator

// Zjištění typu proměnné
echo "\n=== Typy proměnných ===\n";
echo 'Typ $jmeno: ' . gettype($jmeno) . "\n";
echo 'Typ $vek: ' . gettype($vek) . "\n";
echo 'Typ $vyska: ' . gettype($vyska) . "\n";
echo 'Typ $jeStudent: ' . gettype($jeStudent) . "\n";

echo "\n--- Tvůj úkol ---\n";
// TODO: Vytvoř proměnné pro svého oblíbeného hrdinu (jméno, síla, zdraví)
//       a vypiš je na obrazovku
