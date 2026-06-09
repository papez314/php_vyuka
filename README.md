# Myslivecký spolek Branky - Poličná (Web s PHP & SQLite)

Tato složka obsahuje webovou prezentaci pro **Myslivecký spolek Branky - Poličná**. Web byl původně postaven na statických HTML stránkách a byl rozšířen o dynamicé prvky napsané v **PHP 8.3** s databází **SQLite**.

---

## 🚀 Rychlý start

Pro spuštění projektu a funkčnost všech dynamických částí postupujte následovně:

### 1. Inicializace databáze
Nejprve je nutné vytvořit lokální databázový soubor a naplnit ho výchozími daty (články, akcemi a administrátorským účtem). Otevřete terminál a spusťte příkaz:
```bash
php init_spolek.php
```
Tento příkaz vytvoří soubor `spolek.db` v kořenovém adresáři.

### 2. Spuštění PHP serveru
Pro správné spuštění webu použijte vestavěný PHP server (nahrazuje původní Python server):
```bash
php -S 0.0.0.0:8082
```
Web bude dostupný ve vašem prohlížeči na adrese: `http://localhost:8082/index.php`.

---

## 🔑 Administrační rozhraní (CRUD)

Administrace je přístupná na adrese `http://localhost:8082/admin.php`.

- **Uživatelské jméno:** `myslivec`
- **Heslo:** `Les123`

*Heslo je v databázi bezpečně šifrováno pomocí `password_hash()` a je možné jej po přihlášení změnit přímo v administraci.*

### Nabízené funkce administrace:
- **Přidávání, úprava (editace) a mazání aktualit** (článků) na hlavní stránce.
- **Přidávání, úprava (editace) a mazání akcí** v kalendáři na postranním panelu.
- **Změna hesla** administrátora.
- Ochrana proti **CSRF útokům** pomocí validace tokenů v relacích (`session`).

---

## 📂 Struktura projektu

```
Nová složka/
├── archiv/                 - Složka s původními nepoužívanými a statickými verzemi souborů
├── O_nas.html              - Statické HTML stránky spolku
├── cinnost_spolku.html
├── doby_lovu.html
├── fotogalerie.html
├── historie.html
├── kontaktni_udaje.html
├── mapa.html
├── plan_akci.html
├── popis_honitby.html
├── popis_hranic.html
├── seznam_clenu.html
│
├── index.php               - Hlavní dynamicá stránka s vyhledáváním a výpisem aktualit
├── admin.php               - Zabezpečená administrace (Login, CRUD, změna hesla)
│
├── init_spolek.php         - Skript pro vytvoření a nasetování databáze spolek.db
├── spolek.db               - SQLite databáze (obsahuje tabulky: news, events, users)
├── projekt2.css            - Hlavní stylopis webu ( Arial, přírodní lesní barevná paleta)
├── prikaz_start_server.txt  - Příkaz pro rychlé spuštění serveru
└── *.jpg / *.JPG           - Obrázky a fotky použité na webu
```

---

## ⚙️ Technické informace a bezpečnost
- **Vyhledávání**: V `index.php` je integrováno interaktivní fulltextové vyhledávání v PHP, které filtruje články podle nadpisu, obsahu či kategorie.
- **SQLite PDO**: Připojení k databázi využívá ovladač `PDO` v PHP s ošetřením vstupů (Prepared Statements) jako ochrana proti SQL Injection.
- **Hesla**: Heslo se ověřuje bezpečně na serveru metodou `password_verify()`.
