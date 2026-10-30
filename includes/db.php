<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/schema.php';

function db() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// All settings as key => value, with schema defaults for anything missing
function settings() {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (SETTINGS as $group) {
            foreach ($group['fields'] as $key => $f) $cache[$key] = $f['default'] ?? '';
        }
        foreach (db()->query('SELECT skey, svalue FROM settings') as $row) $cache[$row['skey']] = $row['svalue'];
    }
    return $cache;
}

function s($key) { return settings()[$key] ?? ''; }

function items($section) {
    $st = db()->prepare('SELECT * FROM items WHERE section = ? AND active = 1 ORDER BY sort, id');
    $st->execute([$section]);
    return $st->fetchAll();
}

function media_type($path) {
    $ext = strtolower(pathinfo((string)$path, PATHINFO_EXTENSION));
    return ALLOWED_MEDIA[$ext][1] ?? '';
}

// Renders an uploaded image or video; returns '' when no media is set
function media($path, $alt = '', $class = '') {
    if (!$path) return '';
    $src = e(UPLOAD_URL . $path);
    $cls = $class ? ' class="' . e($class) . '"' : '';
    if (media_type($path) === 'video') {
        return '<video' . $cls . ' src="' . $src . '" autoplay muted loop playsinline></video>';
    }
    return '<img' . $cls . ' src="' . $src . '" alt="' . e($alt) . '" loading="lazy">';
}

// Saves an uploaded file from $_FILES[$field]; returns stored filename, null if none, or throws
function save_upload($field) {
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload failed (error code ' . $f['error'] . ').');

    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!isset(ALLOWED_MEDIA[$ext])) throw new RuntimeException('Only JPG, PNG, GIF, WEBP, MP4, WEBM files are allowed.');

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if ($mime !== ALLOWED_MEDIA[$ext][0] && !($ext === 'jpg' && $mime === 'image/jpeg')) {
        throw new RuntimeException('File content does not match its extension.');
    }

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $name)) throw new RuntimeException('Could not save the file.');
    return $name;
}

function delete_upload($name) {
    if ($name && basename($name) === $name && is_file(UPLOAD_DIR . $name)) @unlink(UPLOAD_DIR . $name);
}
