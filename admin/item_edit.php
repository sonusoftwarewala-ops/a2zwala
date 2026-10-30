<?php
require __DIR__ . '/_bootstrap.php';
require_login();

$section = $_GET['section'] ?? '';
if (!isset(SECTIONS[$section])) redirect('index.php');
$def = SECTIONS[$section];
$id  = (int)($_GET['id'] ?? 0);

$item = ['title' => '', 'subtitle' => '', 'body' => '', 'link' => '', 'media' => '', 'extra' => '', 'sort' => 0, 'active' => 1];
if ($id) {
    $st = db()->prepare('SELECT * FROM items WHERE id = ? AND section = ?');
    $st->execute([$id, $section]);
    $item = $st->fetch() ?: redirect('items.php?section=' . urlencode($section));
} else {
    $st = db()->prepare('SELECT COALESCE(MAX(sort), 0) + 10 FROM items WHERE section = ?');
    $st->execute([$section]);
    $item['sort'] = (int)$st->fetchColumn();
}

if (post_too_large()) {
    flash('File is too large for the server upload limit (' . ini_get('post_max_size') . ').', 'err');
    redirect($_SERVER['REQUEST_URI']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    try {
        foreach ($def['fields'] as $col => $f) {
            $item[$col] = $f['type'] === 'media' ? handle_media_field($col, $item['media']) : trim($_POST[$col] ?? '');
        }
        $item['sort']   = (int)($_POST['sort'] ?? 0);
        $item['active'] = empty($_POST['active']) ? 0 : 1;

        $vals = [$item['title'], $item['subtitle'], $item['body'], $item['link'], $item['media'], $item['extra'], $item['sort'], $item['active']];
        if ($id) {
            db()->prepare('UPDATE items SET title=?, subtitle=?, body=?, link=?, media=?, extra=?, sort=?, active=? WHERE id=? AND section=?')
                ->execute(array_merge($vals, [$id, $section]));
        } else {
            db()->prepare('INSERT INTO items (title, subtitle, body, link, media, extra, sort, active, section) VALUES (?,?,?,?,?,?,?,?,?)')
                ->execute(array_merge($vals, [$section]));
        }
        flash('Saved.');
        redirect('items.php?section=' . urlencode($section));
    } catch (RuntimeException $ex) {
        flash($ex->getMessage(), 'err');
        redirect($_SERVER['REQUEST_URI']);
    }
}

admin_header(($id ? 'Edit' : 'Add') . ' — ' . $def['label'], 'sec-' . $section);
?>
<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <?php foreach ($def['fields'] as $col => $f) echo field_input($col, $f, (string)$item[$col]); ?>
  <div class="field"><label>Order</label><input type="text" name="sort" value="<?= (int)$item['sort'] ?>"><small>Lower number shows first.</small></div>
  <div class="field"><label class="check"><input type="checkbox" name="active" value="1" <?= $item['active'] ? 'checked' : '' ?>> Visible on website</label></div>
  <div class="actions">
    <button class="btn">Save</button>
    <a href="items.php?section=<?= e($section) ?>">Cancel</a>
  </div>
</form>
<?php admin_footer(); ?>
