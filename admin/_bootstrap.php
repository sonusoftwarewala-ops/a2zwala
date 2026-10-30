<?php
require_once __DIR__ . '/../includes/db.php';

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

function csrf_field() { return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">'; }

function check_csrf() {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid form token. Go back and reload the page.');
    }
}

function require_login() {
    if (empty($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
}

function flash($msg = null, $type = 'ok') {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $type]; return; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
    return $f;
}

function redirect($url) { header('Location: ' . $url); exit; }

// Upload size limits are enforced by PHP; an oversized POST arrives empty
function post_too_large() {
    return $_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0;
}

function admin_header($title, $active = '') {
    $f = flash();
    ?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · a2zwala admin</title>
<link rel="stylesheet" href="admin.css">
</head><body>
<aside class="side" id="side">
  <a href="index.php" class="brand">a2z<span>wala</span> <small>admin</small></a>
  <nav>
    <a href="index.php" class="<?= $active === 'dash' ? 'on' : '' ?>">🏠 Dashboard</a>
    <p>Page text &amp; images</p>
    <?php foreach (SETTINGS as $g => $def): ?>
      <a href="settings.php?group=<?= e($g) ?>" class="<?= $active === 'set-' . $g ? 'on' : '' ?>"><?= e($def['label']) ?></a>
    <?php endforeach; ?>
    <p>Sections (lists)</p>
    <?php foreach (SECTIONS as $k => $def): ?>
      <a href="items.php?section=<?= e($k) ?>" class="<?= $active === 'sec-' . $k ? 'on' : '' ?>"><?= e($def['label']) ?></a>
    <?php endforeach; ?>
    <p>Account</p>
    <a href="media.php" class="<?= $active === 'media' ? 'on' : '' ?>">🖼 Media library</a>
    <a href="password.php" class="<?= $active === 'pass' ? 'on' : '' ?>">🔑 Change password</a>
    <a href="logout.php">↩ Logout</a>
  </nav>
</aside>
<main class="main">
  <div class="topbar">
    <button class="burger" onclick="document.getElementById('side').classList.toggle('open')">☰</button>
    <h1><?= e($title) ?></h1>
    <a href="../home.php" target="_blank" class="btn ghost">View website ↗</a>
  </div>
  <?php if ($f): ?><div class="alert <?= e($f[1]) ?>"><?= e($f[0]) ?></div><?php endif; ?>
<?php
}

function admin_footer() {
    echo "</main></body></html>";
}

// Shared form control for one field definition
function field_input($name, $def, $value) {
    $type = $def['type'];
    $html = '<div class="field"><label>' . e($def['label']) . '</label>';
    if ($type === 'textarea') {
        $html .= '<textarea name="' . e($name) . '" rows="4">' . e($value) . '</textarea>';
    } elseif ($type === 'select') {
        $html .= '<select name="' . e($name) . '">';
        foreach ($def['options'] as $k => $l) $html .= '<option value="' . e($k) . '"' . ($value === $k ? ' selected' : '') . '>' . e($l) . '</option>';
        $html .= '</select>';
    } elseif ($type === 'image' || $type === 'media') {
        $accept = $type === 'image' ? '.jpg,.jpeg,.png,.gif,.webp' : '.jpg,.jpeg,.png,.gif,.webp,.mp4,.webm';
        if ($value) {
            $html .= '<div class="preview">' . str_replace(UPLOAD_URL, '../' . UPLOAD_URL, media($value)) . '</div>'
                   . '<label class="check"><input type="checkbox" name="' . e($name) . '__remove" value="1"> Remove current file</label>';
        }
        $html .= '<input type="file" name="' . e($name) . '" accept="' . $accept . '">';
        $html .= '<small>' . ($type === 'image' ? 'JPG, PNG, GIF, WEBP' : 'Image (JPG, PNG, GIF, WEBP) or video (MP4, WEBM)') . '</small>';
    } else {
        $html .= '<input type="text" name="' . e($name) . '" value="' . e($value) . '">';
    }
    if (!empty($def['help'])) $html .= '<small>' . e($def['help']) . '</small>';
    return $html . '</div>';
}

// Applies upload / remove for a media field; returns the new stored value
function handle_media_field($name, $current) {
    $new = save_upload($name);
    if ($new !== null) { delete_upload($current); return $new; }
    if (!empty($_POST[$name . '__remove'])) { delete_upload($current); return ''; }
    return $current;
}
