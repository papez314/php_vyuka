# PHP Výuka

Repozitář pro výuku PHP na střední škole. Používá PHP 8.3+ s moderní syntaxí a striktním typováním.

## Návod pro studenty (Windows)

### Krok 1: Vytvoření GitHub účtu (jednorázově)

1. Jdi na https://github.com
2. Klikni **Sign up**
3. Zaregistruj se (ideálně školním emailem pro GitHub Education - více hodin zdarma)

### Krok 2: Vytvoření vlastní kopie repozitáře

1. Jdi na https://github.com/radimsvacek/t2a-php-vyuka
2. Klikni **Use this template** → **Create a new repository**
3. Pojmenuj si ho (např. `php-vyuka`)
4. Nech zaškrtnuté **Public**
5. Klikni **Create repository**

### Krok 3: Spuštění vývojového prostředí

#### Varianta A: VS Code v prohlížeči (doporučeno)

1. Ve svém novém repozitáři klikni zelené tlačítko **Code**
2. Záložka **Codespaces** → **Create codespace on master**
3. Počkej 1-2 minuty než se načte VS Code v prohlížeči
4. Hotovo! Můžeš programovat.

#### Varianta B: Lokální VS Code (lepší výkon)

Pokud máš nainstalovaný VS Code na počítači:

1. Nainstaluj rozšíření **GitHub Codespaces** ve VS Code
   - Otevři VS Code → Ctrl+Shift+X → vyhledej "GitHub Codespaces" → Install
2. Klikni na ikonu **Remote Explorer** v levém panelu (nebo Ctrl+Shift+P → "Codespaces: Connect to Codespace")
3. Přihlas se do GitHubu
4. Vyber svůj codespace nebo vytvoř nový
5. VS Code se připojí ke vzdálenému prostředí

**Výhody lokálního VS Code:**
- Rychlejší odezva
- Fungují všechna tvoje nastavení a rozšíření
- Lepší práce s klávesnicí

### Krok 4: Práce ukončena - uložení

Na konci hodiny (nebo kdykoliv chceš uložit):

```bash
git add .
git commit -m "Popis co jsem udělal"
git push
```

## Struktura projektu

```
lekce/          - PHP skripty pro procvičování (spouští se v terminálu)
public/         - webové stránky (spouští se přes PHP server)
```

## Postup lekcí

| Lekce | Téma | Spuštění |
|-------|------|----------|
| 01 | Proměnné a datové typy | `php lekce/01-promenne.php` |
| 02 | Podmínky (if, match) | `php lekce/02-podminky.php` |
| 03 | Cykly (for, while, foreach) | `php lekce/03-cykly.php` |
| 04 | Pole (arrays) | `php lekce/04-pole.php` |
| 05 | Funkce | `php lekce/05-funkce.php` |

Každá lekce obsahuje příklady a na konci **úkol k vypracování** (hledej `// TODO:`).

## Spuštění PHP

### Skripty z terminálu

Otevři terminál (Ctrl+`) a spusť:

```bash
php lekce/01-promenne.php
```

### Webový server

Pro spuštění webových stránek:

```bash
php -S 0.0.0.0:8080 -t public
```

Po spuštění:
- V prohlížeči (varianta A): Klikni na odkaz v terminálu, nebo otevři záložku **Ports** a klikni na port 8080
- V lokálním VS Code (varianta B): Automaticky se nabídne otevření v prohlížeči

## Moderní PHP 8.3+

V lekcích používáme moderní PHP syntaxi:

```php
<?php

declare(strict_types=1);  // Striktní typová kontrola
```

### Co můžeme typovat

| Kde | Příklad | Funguje |
|-----|---------|---------|
| Parametry funkcí | `function foo(string $jmeno)` | Ano |
| Návratové hodnoty | `function foo(): string` | Ano |
| Vlastnosti tříd | `public string $jmeno;` | Ano |
| Lokální proměnné | `string $jmeno = 'Jan';` | **Ne** (PHP to nepodporuje) |

### Moderní syntaxe

- `match` expression - moderní náhrada za switch
- Arrow funkce `fn(int $x): int => $x * 2`
- Pojmenované argumenty `funkce(vek: 25, jmeno: 'Jan')`
- Null coalescing `$value ?? 'default'`
- Union typy `int|float|string`
- Nullable typy `?string`

## Tipy pro VS Code

| Klávesa | Akce |
|---------|------|
| Ctrl+S | Uložit soubor |
| Ctrl+` | Otevřít/zavřít terminál |
| Ctrl+Shift+P | Příkazová paleta |
| Ctrl+P | Rychlé otevření souboru |
| Ctrl+/ | Zakomentovat řádek |

## Časté problémy

### Codespace se nespouští
- Zkontroluj internetové připojení
- Zkus obnovit stránku (F5)
- Počkej chvíli a zkus znovu

### Došly hodiny v Codespaces
- Základní účet má 60 hodin/měsíc
- S GitHub Education (školní email) dostaneš více
- Hodiny se resetují každý měsíc

### Ztratil jsem práci
- Pokud jsi neudělal commit+push, práce je stále v codespace (pokud nebyl smazán)
- Jdi na github.com → Your codespaces → najdi svůj codespace
