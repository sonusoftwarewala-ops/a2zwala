<?php
require __DIR__ . '/_bootstrap.php';

if (!empty($_SESSION['admin_id'])) redirect('index.php');

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    // Slow down brute-force attempts per session
    $_SESSION['login_fails'] = $_SESSION['login_fails'] ?? 0;
    if ($_SESSION['login_fails'] >= 5) sleep(2);

    $st = db()->prepare('SELECT id, password_hash FROM admins WHERE username = ?');
    $st->execute([trim($_POST['username'] ?? '')]);
    $a = $st->fetch();
    if ($a && password_verify($_POST['password'] ?? '', $a['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['login_fails'] = 0;
        redirect('index.php');
    }
    $_SESSION['login_fails']++;
    $err = 'Wrong username or password.';
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login · a2zwala admin</title><link rel="stylesheet" href="admin.css"></head>
<body><div class="login"><div class="card">
  <h1 style="margin:0 0 4px">a2z<span style="color:#e53935">wala</span> admin</h1>
  <p style="color:#6e6e73;margin:0 0 20px">Sign in to manage the website.</p>
  <?php if ($err): ?><div class="alert err"><?= e($err) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="field"><label>Username</label><input type="text" name="username" required autofocus></div>
    <div class="field"><label>Password</label><input type="password" name="password" required></div>
    <button class="btn" style="width:100%">Login</button>
  </form>
</div></div></body></html>
