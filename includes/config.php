<?php
// Database settings (XAMPP default: root / no password)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'a2zwala');
define('DB_USER', 'root');
define('DB_PASS', '');

// Uploads (images / videos)
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');
define('ALLOWED_MEDIA', [
    'jpg'  => ['image/jpeg', 'image'],
    'jpeg' => ['image/jpeg', 'image'],
    'png'  => ['image/png', 'image'],
    'gif'  => ['image/gif', 'image'],
    'webp' => ['image/webp', 'image'],
    'mp4'  => ['video/mp4', 'video'],
    'webm' => ['video/webm', 'video'],
]);
