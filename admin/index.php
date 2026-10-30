<?php
require __DIR__ . '/_bootstrap.php';
require_login();

$counts = [];
foreach (db()->query('SELECT section, COUNT(*) c FROM items GROUP BY section') as $r) $counts[$r['section']] = $r['c'];
$files = is_dir(UPLOAD_DIR) ? count(glob(UPLOAD_DIR . '*.*')) : 0;

admin_header('Dashboard', 'dash');
?>
<div class="card">
  <h2 style="margin-top:0">Welcome 👋</h2>
  <p style="color:#6e6e73;margin-bottom:0">Change any text, image or video on the website from here. Changes show on the site immediately.
  Uploaded files: <b><?= $files ?></b>.</p>
</div>

<h3>Page text &amp; images</h3>
<div class="grid">
  <?php foreach (SETTINGS as $g => $def): ?>
    <a class="tile" href="settings.php?group=<?= e($g) ?>"><b><?= e($def['label']) ?></b><span><?= count($def['fields']) ?> fields</span></a>
  <?php endforeach; ?>
</div>

<h3 style="margin-top:30px">Sections (add / edit / delete / reorder)</h3>
<div class="grid">
  <?php foreach (SECTIONS as $k => $def): ?>
    <a class="tile" href="items.php?section=<?= e($k) ?>"><b><?= e($def['label']) ?></b><span><?= (int)($counts[$k] ?? 0) ?> items</span></a>
  <?php endforeach; ?>
</div>
<?php admin_footer(); ?>
