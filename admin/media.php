<?php
require __DIR__ . '/_bootstrap.php';
require_login();

// Files still referenced by settings or items
$used = [];
foreach (db()->query("SELECT svalue FROM settings WHERE svalue <> ''") as $r) $used[$r['svalue']] = true;
foreach (db()->query("SELECT media FROM items WHERE media <> ''") as $r) $used[$r['media']] = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $name = basename($_POST['file'] ?? '');
    if ($name && !isset($used[$name])) { delete_upload($name); flash('File deleted.'); }
    else flash('This file is in use on the website and cannot be deleted here.', 'err');
    redirect('media.php');
}

$files = is_dir(UPLOAD_DIR) ? glob(UPLOAD_DIR . '*.*') : [];
usort($files, fn($a, $b) => filemtime($b) - filemtime($a));

admin_header('Media library', 'media');
?>
<div class="card">
  <p style="margin-top:0;color:#6e6e73">All uploaded images and videos. Upload new files from the edit forms of each section. Unused files can be deleted here.</p>
  <?php if (!$files): ?><p>No uploads yet.</p><?php endif; ?>
  <div class="media-grid">
    <?php foreach ($files as $path): $n = basename($path); ?>
      <figure>
        <?= str_replace(UPLOAD_URL, '../' . UPLOAD_URL, media($n)) ?>
        <figcaption>
          <?= e($n) ?><br><?= round(filesize($path) / 1024) ?> KB ·
          <?php if (isset($used[$n])): ?><span class="badge on">in use</span>
          <?php else: ?>
            <form method="post" class="inline" onsubmit="return confirm('Delete this file?')"><?= csrf_field() ?><input type="hidden" name="file" value="<?= e($n) ?>"><button class="btn small danger">Delete</button></form>
          <?php endif; ?>
        </figcaption>
      </figure>
    <?php endforeach; ?>
  </div>
</div>
<?php admin_footer(); ?>
