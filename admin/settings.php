<?php
require __DIR__ . '/_bootstrap.php';
require_login();

$group = $_GET['group'] ?? 'general';
if (!isset(SETTINGS[$group])) redirect('index.php');
$def = SETTINGS[$group];

if (post_too_large()) {
    flash('File is too large for the server upload limit (' . ini_get('post_max_size') . ').', 'err');
    redirect('settings.php?group=' . urlencode($group));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $st = db()->prepare('INSERT INTO settings (skey, svalue) VALUES (?, ?) ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)');
    try {
        foreach ($def['fields'] as $key => $f) {
            if ($f['type'] === 'image' || $f['type'] === 'media') {
                $val = handle_media_field($key, s($key));
            } else {
                $val = trim($_POST[$key] ?? '');
            }
            $st->execute([$key, $val]);
        }
        flash('Saved.');
    } catch (RuntimeException $ex) {
        flash($ex->getMessage(), 'err');
    }
    redirect('settings.php?group=' . urlencode($group));
}

admin_header($def['label'], 'set-' . $group);
?>
<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <?php foreach ($def['fields'] as $key => $f) echo field_input($key, $f, s($key)); ?>
  <button class="btn">Save changes</button>
</form>
<?php admin_footer(); ?>
