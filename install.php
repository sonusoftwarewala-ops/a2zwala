<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/schema.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$msg = ''; $ok = false; $installed = false;

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $pdo->exec('USE `' . DB_NAME . '`');

    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(60) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        skey VARCHAR(80) PRIMARY KEY,
        svalue TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $pdo->exec("CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(60) NOT NULL,
        title VARCHAR(255) DEFAULT '',
        subtitle VARCHAR(255) DEFAULT '',
        body TEXT,
        link VARCHAR(500) DEFAULT '',
        media VARCHAR(255) DEFAULT '',
        extra TEXT,
        sort INT DEFAULT 0,
        active TINYINT(1) DEFAULT 1,
        INDEX (section, sort)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Seed default content only once (INSERT IGNORE keeps admin edits)
    $st = $pdo->prepare('INSERT IGNORE INTO settings (skey, svalue) VALUES (?, ?)');
    foreach (SETTINGS as $group) foreach ($group['fields'] as $k => $f) $st->execute([$k, $f['default'] ?? '']);

    $ins = $pdo->prepare('INSERT INTO items (section, title, subtitle, body, link, media, extra, sort) VALUES (?,?,?,?,?,?,?,?)');
    $has = $pdo->prepare('SELECT COUNT(*) FROM items WHERE section = ?');
    foreach (SECTIONS as $sec => $def) {
        $has->execute([$sec]);
        if ($has->fetchColumn() > 0) continue;
        foreach ($def['seed'] as $i => $r) {
            $ins->execute([$sec, $r['title'] ?? '', $r['subtitle'] ?? '', $r['body'] ?? '', $r['link'] ?? '', '', $r['extra'] ?? '', ($i + 1) * 10]);
        }
    }

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

    $installed = $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0;

    if (!$installed && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        if (!preg_match('/^[A-Za-z0-9_.-]{3,60}$/', $u)) $msg = 'Username: 3–60 letters, numbers, _ . -';
        elseif (strlen($p) < 6) $msg = 'Password must be at least 6 characters.';
        elseif ($p !== ($_POST['password2'] ?? '')) $msg = 'Passwords do not match.';
        else {
            $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)')->execute([$u, password_hash($p, PASSWORD_DEFAULT)]);
            $installed = true; $ok = true;
        }
    }
} catch (PDOException $ex) {
    $msg = 'Database error: ' . $ex->getMessage() . ' — check that MySQL is running in XAMPP and includes/config.php is correct.';
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Install a2zwala</title>
<style>
body{font-family:"Segoe UI",Arial,sans-serif;background:#f5f5f7;color:#1d1d1f;display:grid;place-items:center;min-height:100vh;margin:0;padding:16px}
.box{background:#fff;border-radius:20px;padding:36px;max-width:420px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.08)}
h1{margin:0 0 6px;font-size:26px}p{color:#6e6e73}label{display:block;font-size:14px;margin:14px 0 6px}
input{width:100%;padding:11px 14px;border:1px solid #d2d2d7;border-radius:10px;font:inherit;box-sizing:border-box}
button,.btn{display:inline-block;margin-top:20px;background:#0071e3;color:#fff;border:0;padding:12px 22px;border-radius:999px;font:inherit;cursor:pointer;text-decoration:none}
.err{background:#fdecea;color:#b3261e;padding:10px 14px;border-radius:10px;font-size:14px}.ok{background:#e7f6ec;color:#1d6b35;padding:10px 14px;border-radius:10px}
</style></head><body><div class="box">
<h1>a2zwala setup</h1>
<?php if ($msg): ?><p class="err"><?= e($msg) ?></p><?php endif; ?>
<?php if ($installed): ?>
  <p class="ok"><?= $ok ? 'Installed! Admin account created.' : 'Already installed. Database <b>' . e(DB_NAME) . '</b> is ready.' ?></p>
  <p>For security, delete or rename <b>install.php</b> now.</p>
  <a class="btn" href="admin/login.php">Go to admin login</a> &nbsp; <a href="home.php">View website</a>
<?php elseif (!str_starts_with($msg, 'Database error')): ?>
  <p>Database <b><?= e(DB_NAME) ?></b> and tables are ready. Create your admin account:</p>
  <form method="post">
    <label>Username</label><input name="username" required value="<?= e($_POST['username'] ?? 'admin') ?>">
    <label>Password</label><input type="password" name="password" required minlength="6">
    <label>Confirm password</label><input type="password" name="password2" required minlength="6">
    <button>Create admin</button>
  </form>
<?php endif; ?>
</div></body></html>
