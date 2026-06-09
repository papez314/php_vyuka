<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$dbPath = __DIR__ . '/spolek.db';
$message = "";
$messageType = "error"; // error or success

// Connect to Database
try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Připojení k databázi selhalo: " . $e->getMessage());
}

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Handle Login Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Neplatný CSRF token.');
    }
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username !== '' && $password !== '') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $user['username'];
            header('Location: admin.php');
            exit;
        } else {
            $message = "Nesprávné uživatelské jméno nebo heslo.";
            $messageType = "error";
        }
    } else {
        $message = "Vyplňte prosím všechna pole.";
        $messageType = "error";
    }
}

// Check if logged in
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// If not logged in, show login screen
if (!$isLoggedIn) {
    ?>
    <!DOCTYPE html>
    <html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Přihlášení do administrace</title>
        <link rel="stylesheet" href="projekt2.css">
        <style>
            .login-container {
                max-width: 400px;
                margin: 80px auto;
                padding: 30px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                border-top: 8px solid #2e3d23;
            }
            .login-container h1 {
                color: #2e3d23;
                text-align: center;
                margin-bottom: 25px;
                font-size: 1.8rem;
                font-family: Arial, sans-serif;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                font-weight: bold;
                margin-bottom: 6px;
                color: #4a5c3e;
            }
            .form-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 1rem;
                background-color: #faf9f5;
                box-sizing: border-box;
            }
            .form-group input:focus {
                border-color: #2e3d23;
                outline: none;
                background-color: #fff;
                box-shadow: 0 0 5px rgba(46,61,35,0.2);
            }
            .btn-login {
                background: #2e3d23;
                color: white;
                padding: 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                width: 100%;
                font-size: 1rem;
                transition: background 0.3s;
            }
            .btn-login:hover {
                background: #1a2416;
            }
            .alert {
                padding: 12px;
                border-radius: 4px;
                margin-bottom: 20px;
                font-size: 0.9rem;
                text-align: center;
                font-weight: bold;
            }
            .alert-error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            .back-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: #4a5c3e;
                text-decoration: none;
                font-weight: bold;
            }
            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Administrace MS</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            
            <form action="admin.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                
                <div class="form-group">
                    <label for="username">Uživatelské jméno</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Heslo</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                
                <button type="submit" name="login" class="btn-login">Přihlásit se</button>
            </form>
            
            <a href="index.php" class="back-link">← Zpět na hlavní web</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// POST handlers for logged-in admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Neplatný CSRF token.');
    }
    
    // Add News
    if (isset($_POST['add_news'])) {
        $title = trim($_POST['title'] ?? '');
        $category_tag = trim($_POST['category_tag'] ?? 'Aktuality');
        $published_date = trim($_POST['published_date'] ?? date('j. n. Y'));
        $content = trim($_POST['content'] ?? '');
        
        if ($title !== '' && $content !== '') {
            $stmt = $pdo->prepare("INSERT INTO news (title, category_tag, published_date, content) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $category_tag, $published_date, $content]);
            $_SESSION['flash_message'] = "Aktualita byla úspěšně přidána.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        } else {
            $message = "Nadpis a obsah aktuality musí být vyplněny.";
            $messageType = "error";
        }
    }
    
    // Update News
    if (isset($_POST['update_news'])) {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $category_tag = trim($_POST['category_tag'] ?? 'Aktuality');
        $published_date = trim($_POST['published_date'] ?? date('j. n. Y'));
        $content = trim($_POST['content'] ?? '');
        
        if ($id > 0 && $title !== '' && $content !== '') {
            $stmt = $pdo->prepare("UPDATE news SET title = ?, category_tag = ?, published_date = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $category_tag, $published_date, $content, $id]);
            $_SESSION['flash_message'] = "Aktualita byla úspěšně upravena.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        } else {
            $message = "Nadpis a obsah aktuality musí být vyplněny.";
            $messageType = "error";
        }
    }
    
    // Delete News
    if (isset($_POST['delete_news'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_message'] = "Aktualita byla úspěšně smazána.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        }
    }
    
    // Add Event
    if (isset($_POST['add_event'])) {
        $event_date = trim($_POST['event_date'] ?? '');
        $title = trim($_POST['event_title'] ?? '');
        
        if ($event_date !== '' && $title !== '') {
            $stmt = $pdo->prepare("INSERT INTO events (event_date, title) VALUES (?, ?)");
            $stmt->execute([$event_date, $title]);
            $_SESSION['flash_message'] = "Akce byla úspěšně přidána do kalendáře.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        } else {
            $message = "Datum a název akce musí být vyplněny.";
            $messageType = "error";
        }
    }
    
    // Update Event
    if (isset($_POST['update_event'])) {
        $id = (int)($_POST['id'] ?? 0);
        $event_date = trim($_POST['event_date'] ?? '');
        $title = trim($_POST['event_title'] ?? '');
        
        if ($id > 0 && $event_date !== '' && $title !== '') {
            $stmt = $pdo->prepare("UPDATE events SET event_date = ?, title = ? WHERE id = ?");
            $stmt->execute([$event_date, $title, $id]);
            $_SESSION['flash_message'] = "Akce byla úspěšně upravena.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        } else {
            $message = "Datum a název akce musí být vyplněny.";
            $messageType = "error";
        }
    }
    
    // Delete Event
    if (isset($_POST['delete_event'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['flash_message'] = "Akce byla úspěšně smazána.";
            $_SESSION['flash_message_type'] = "success";
            header('Location: admin.php');
            exit;
        }
    }
    
    // Change Password
    if (isset($_POST['change_password'])) {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';
        
        if ($oldPassword !== '' && $newPassword !== '') {
            if ($newPassword !== $newPasswordConfirm) {
                $message = "Nová hesla se neshodují.";
                $messageType = "error";
            } else {
                // Verify old password
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$_SESSION['admin_user']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($oldPassword, $user['password'])) {
                    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$newHash, $user['id']]);
                    $_SESSION['flash_message'] = "Heslo bylo úspěšně změněno.";
                    $_SESSION['flash_message_type'] = "success";
                    header('Location: admin.php');
                    exit;
                } else {
                    $message = "Původní heslo není správné.";
                    $messageType = "error";
                }
            }
        } else {
            $message = "Vyplňte prosím všechna pole pro změnu hesla.";
            $messageType = "error";
        }
    }
}

// Retrieve flash messages
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $messageType = $_SESSION['flash_message_type'] ?? 'success';
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_message_type']);
}

// GET route handling for editing
$action = $_GET['action'] ?? 'dashboard';

// Fetch lists for dashboard
$newsList = [];
$eventsList = [];
if ($action === 'dashboard') {
    $newsList = $pdo->query("SELECT * FROM news ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    $eventsList = $pdo->query("SELECT * FROM events ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch edit news item
$editNewsItem = null;
if ($action === 'edit_news') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $editNewsItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$editNewsItem) {
        header('Location: admin.php');
        exit;
    }
}

// Fetch edit event item
$editEventItem = null;
if ($action === 'edit_event') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $editEventItem = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$editEventItem) {
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace - Myslivecký spolek Poličná</title>
    <link rel="stylesheet" href="projekt2.css">
    <style>
        body {
            background-color: #f2f0e6;
            margin: 0;
            padding: 0;
        }
        .admin-header {
            background-color: #2e3d23;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #1a2416;
        }
        .admin-header h1 {
            margin: 0;
            font-size: 1.4rem;
        }
        .admin-header h1 a {
            color: white;
            text-decoration: none;
        }
        .admin-user-info {
            font-size: 0.9rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .admin-user-info span {
            color: #a3c18c;
        }
        .btn-logout {
            color: #ff9494;
            text-decoration: none;
            border: 1px solid #ff9494;
            padding: 4px 10px;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: #ff9494;
            color: #2e3d23;
        }
        .admin-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .admin-nav-links {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }
        .admin-card {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 6px;
            border-left: 8px solid #2e3d23;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .admin-card h2 {
            color: #2e3d23;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.4rem;
        }
        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-primary {
            background: #2e3d23;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: #1a2416;
        }
        .btn-danger {
            background: #c53030;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
            font-size: 0.85rem;
        }
        .btn-danger:hover {
            background: #9b2c2c;
        }
        .btn-edit {
            background: #dd6b20;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
            font-size: 0.85rem;
        }
        .btn-edit:hover {
            background: #c05621;
        }
        .btn-secondary {
            background: #718096;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
            font-size: 0.9rem;
        }
        .btn-secondary:hover {
            background: #4a5568;
        }
        .crud-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .crud-table th, .crud-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .crud-table th {
            background-color: #f7fafc;
            color: #4a5c3e;
            font-weight: bold;
        }
        .crud-table tr:hover {
            background-color: #faf9f5;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .form-control label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2e3d23;
        }
        .form-control input, .form-control textarea, .form-control select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            background-color: #fdfdfb;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-control input:focus, .form-control textarea:focus {
            border-color: #2e3d23;
            outline: none;
            background-color: white;
            box-shadow: 0 0 5px rgba(46,61,35,0.15);
        }
        .category-badge {
            background: #4a5c3e;
            color: white;
            padding: 3px 8px;
            font-size: 0.75rem;
            border-radius: 3px;
            font-weight: bold;
        }
        .category-badge.secondary {
            background: #a3c18c;
            color: #2e3d23;
        }
        .flex-buttons {
            display: flex;
            gap: 8px;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .admin-header {
                flex-direction: column;
                gap: 10px;
                text-align: center;
                padding: 15px;
            }
            .admin-nav-links {
                flex-direction: column;
                gap: 8px;
            }
            .crud-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

    <header class="admin-header">
        <h1><a href="index.php">⚙️ Administrace MS Poličná</a></h1>
        <div class="admin-user-info">
            Přihlášen: <span><?= htmlspecialchars($_SESSION['admin_user']) ?></span>
            <a href="admin.php?action=logout" class="btn-logout">Odhlásit se</a>
        </div>
    </header>

    <div class="admin-container">
        
        <div class="admin-nav-links">
            <a href="index.php" class="btn-secondary">← Zpět na hlavní web</a>
            <?php if ($action !== 'dashboard'): ?>
                <a href="admin.php" class="btn-secondary">Zpět na přehled administrace</a>
            <?php else: ?>
                <a href="admin.php?action=change_password" class="btn-primary">Změnit heslo administrátora</a>
            <?php endif; ?>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType === 'success' ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- EDIT NEWS VIEW -->
        <?php if ($action === 'edit_news' && $editNewsItem): ?>
            <div class="admin-card">
                <h2>Upravit aktualitu #<?= $editNewsItem['id'] ?></h2>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= $editNewsItem['id'] ?>">
                    
                    <div class="form-control">
                        <label for="news_title">Nadpis aktuality</label>
                        <input type="text" id="news_title" name="title" value="<?= htmlspecialchars($editNewsItem['title']) ?>" required>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-control">
                            <label for="news_category">Kategorie (tag)</label>
                            <input type="text" id="news_category" name="category_tag" value="<?= htmlspecialchars($editNewsItem['category_tag']) ?>" required>
                        </div>
                        <div class="form-control">
                            <label for="news_date">Datum publikace</label>
                            <input type="text" id="news_date" name="published_date" value="<?= htmlspecialchars($editNewsItem['published_date']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-control">
                        <label for="news_content">Obsah aktuality</label>
                        <textarea id="news_content" name="content" rows="8" required><?= htmlspecialchars($editNewsItem['content']) ?></textarea>
                    </div>
                    
                    <div class="flex-buttons">
                        <button type="submit" name="update_news" class="btn-primary">Uložit změny</button>
                        <a href="admin.php" class="btn-secondary">Zrušit</a>
                    </div>
                </form>
            </div>

        <!-- EDIT EVENT VIEW -->
        <?php elseif ($action === 'edit_event' && $editEventItem): ?>
            <div class="admin-card">
                <h2>Upravit akci #<?= $editEventItem['id'] ?></h2>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= $editEventItem['id'] ?>">
                    
                    <div class="form-grid">
                        <div class="form-control">
                            <label for="event_date">Datum akce (např. 15. 1.)</label>
                            <input type="text" id="event_date" name="event_date" value="<?= htmlspecialchars($editEventItem['event_date']) ?>" required>
                        </div>
                        <div class="form-control">
                            <label for="event_title">Název akce</label>
                            <input type="text" id="event_title" name="event_title" value="<?= htmlspecialchars($editEventItem['title']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="flex-buttons">
                        <button type="submit" name="update_event" class="btn-primary">Uložit změny</button>
                        <a href="admin.php" class="btn-secondary">Zrušit</a>
                    </div>
                </form>
            </div>

        <!-- CHANGE PASSWORD VIEW -->
        <?php elseif ($action === 'change_password'): ?>
            <div class="admin-card">
                <h2>Změna hesla administrátora</h2>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    
                    <div class="form-control">
                        <label for="old_password">Stávající heslo</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-control">
                            <label for="new_password">Nové heslo</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-control">
                            <label for="new_password_confirm">Potvrzení nového hesla</label>
                            <input type="password" id="new_password_confirm" name="new_password_confirm" required>
                        </div>
                    </div>
                    
                    <div class="flex-buttons">
                        <button type="submit" name="change_password" class="btn-primary">Změnit heslo</button>
                        <a href="admin.php" class="btn-secondary">Zrušit</a>
                    </div>
                </form>
            </div>

        <!-- DASHBOARD VIEW -->
        <?php else: ?>
            
            <div class="form-grid">
                <!-- ADD NEWS FORM -->
                <div class="admin-card">
                    <h2>Nová aktualita</h2>
                    <form action="admin.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        
                        <div class="form-control">
                            <label for="new_news_title">Nadpis aktuality</label>
                            <input type="text" id="new_news_title" name="title" placeholder="např. Výroční členská schůze 2026" required>
                        </div>
                        
                        <div class="form-grid" style="gap:10px;">
                            <div class="form-control">
                                <label for="new_news_category">Kategorie (tag)</label>
                                <input type="text" id="new_news_category" name="category_tag" value="Aktuality" required>
                            </div>
                            <div class="form-control">
                                <label for="new_news_date">Datum</label>
                                <input type="text" id="new_news_date" name="published_date" value="<?= date('j. n. Y') ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-control">
                            <label for="new_news_content">Obsah aktuality</label>
                            <textarea id="new_news_content" name="content" rows="4" placeholder="Obsah sdělení pro členy a veřejnost..." required></textarea>
                        </div>
                        
                        <button type="submit" name="add_news" class="btn-primary" style="width:100%;">Publikovat aktualitu</button>
                    </form>
                </div>

                <!-- ADD EVENT FORM -->
                <div class="admin-card">
                    <h2>Nová akce v kalendáři</h2>
                    <form action="admin.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        
                        <div class="form-control">
                            <label for="new_event_date">Datum akce (např. 24. 1.)</label>
                            <input type="text" id="new_event_date" name="event_date" placeholder="15. 1." required>
                        </div>
                        
                        <div class="form-control">
                            <label for="new_event_title">Název akce</label>
                            <input type="text" id="new_event_title" name="event_title" placeholder="např. Společný hon nebo Ples" required>
                        </div>
                        
                        <button type="submit" name="add_event" class="btn-primary" style="width:100%; margin-top: 15px;">Přidat do kalendáře</button>
                    </form>
                </div>
            </div>

            <!-- NEWS LIST -->
            <div class="admin-card">
                <h2>Aktuální články (Aktuality)</h2>
                <?php if (!empty($newsList)): ?>
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Datum</th>
                                <th style="width: 100px;">Kategorie</th>
                                <th>Nadpis</th>
                                <th style="width: 160px; text-align: right;">Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($newsList as $news): ?>
                                <tr>
                                    <td><?= htmlspecialchars($news['published_date']) ?></td>
                                    <td><span class="category-badge <?= $news['category_tag'] !== 'Aktuality' ? 'secondary' : '' ?>"><?= htmlspecialchars($news['category_tag']) ?></span></td>
                                    <td><strong><?= htmlspecialchars($news['title']) ?></strong></td>
                                    <td style="text-align: right;">
                                        <div class="flex-buttons" style="justify-content: flex-end;">
                                            <a href="admin.php?action=edit_news&id=<?= $news['id'] ?>" class="btn-edit">Upravit</a>
                                            <form action="admin.php" method="POST" onsubmit="return confirm('Opravdu chcete smazat tuto aktualitu?');" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                                <input type="hidden" name="id" value="<?= $news['id'] ?>">
                                                <button type="submit" name="delete_news" class="btn-danger">Smazat</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Žádné aktuality v databázi.</p>
                <?php endif; ?>
            </div>

            <!-- EVENTS LIST -->
            <div class="admin-card">
                <h2>Plánované akce (Kalendář)</h2>
                <?php if (!empty($eventsList)): ?>
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">Datum akce</th>
                                <th>Název akce</th>
                                <th style="width: 160px; text-align: right;">Akce</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eventsList as $event): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($event['event_date']) ?></strong></td>
                                    <td><?= htmlspecialchars($event['title']) ?></td>
                                    <td style="text-align: right;">
                                        <div class="flex-buttons" style="justify-content: flex-end;">
                                            <a href="admin.php?action=edit_event&id=<?= $event['id'] ?>" class="btn-edit">Upravit</a>
                                            <form action="admin.php" method="POST" onsubmit="return confirm('Opravdu chcete smazat tuto akci?');" style="display:inline;">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                                <button type="submit" name="delete_event" class="btn-danger">Smazat</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Žádné plánované akce v databázi.</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>