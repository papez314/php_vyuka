<?php

declare(strict_types=1);

/**
 * Lekce 2: Podmínky
 * Spuštění: php lekce/02-podminky.php
 */

$vek = 17;

// Jednoduchá podmínka
echo "=== If/Else ===\n";
if ($vek >= 18) {
    echo "Jsi plnoletý\n";
} else {
    echo "Nejsi plnoletý\n";
}

// Podmínka s více větvemi
echo "\n=== If/Elseif/Else ===\n";
$znamka = 2;

if ($znamka === 1) {
    echo "Výborně!\n";
} elseif ($znamka === 2) {
    echo "Chvalitebně\n";
} elseif ($znamka === 3) {
    echo "Dobře\n";
} elseif ($znamka === 4) {
    echo "Dostatečně\n";
} else {
    echo "Nedostatečně\n";
}

// Match expression (PHP 8.0+) - moderní náhrada za switch
echo "\n=== Match expression ===\n";
$den = 'pondělí';

$typDne = match ($den) {
    'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek' => 'Pracovní den',
    'sobota', 'neděle' => 'Víkend',
    default => 'Neznámý den',
};

echo "{$den}: {$typDne}\n";

// Match s výrazem
echo "\n=== Match s podmínkou ===\n";
$teplota = 25;

$pocasi = match (true) {
    $teplota < 0 => 'Mrzne',
    $teplota < 10 => 'Zima',
    $teplota < 20 => 'Chladno',
    $teplota < 30 => 'Teplo',
    default => 'Horko',
};

echo "Při {$teplota}°C je: {$pocasi}\n";

// Ternární operátor - zkrácený zápis if/else
echo "\n=== Ternární operátor ===\n";
$stav = $vek >= 18 ? 'plnoletý' : 'nezletilý';
echo "Stav: {$stav}\n";

// Null coalescing operátor
echo "\n=== Null coalescing ===\n";
$uzivatel = null;
$jmeno = $uzivatel ?? 'Anonym';  // Pokud je $uzivatel null, použij 'Anonym'
echo "Uživatel: {$jmeno}\n";

echo "\n--- Tvůj úkol ---\n";
// TODO: Napiš podmínku (použij match), která podle skóre (0-100) vypíše známku
//       90-100 = 1, 75-89 = 2, 50-74 = 3, 25-49 = 4, 0-24 = 5
