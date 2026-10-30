<?php
require __DIR__ . '/_bootstrap.php';
require_login();

$section = $_GET['section'] ?? '';
if (!isset(SECTIONS[$section])) redirect('index.php');
$def  = SECTIONS[$section];
$self = 'items.php?section=' . urlencode($section);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete' && $id) {
        $st = db()->prepare('SELECT media FROM items WHERE id = ? AND section = ?');
        $st->execute([$id, $section]);
        if ($row = $st->fetch()) {
            delete_upload($row['media']);
            db()->prepare('DELETE FROM items WHERE id = ?')->execute([$id]);
            flash('Item deleted.');
        }
    } elseif ($action === 'toggle' && $id) {
        db()->prepare('UPDATE items SET active = 1 - active WHERE id = ? AND section = ?')->execute([$id, $section]);
        flash('Visibility updated.');
    } elseif ($action === 'sort') {
        $st = db()->prepare('UPDATE items SET sort = ? WHERE id = ? AND section = ?');
        foreach ((array)($_POST['sort'] ?? []) as $iid => $val) $st->execute([(int)$val, (int)$iid, $section]);
        flash('Order saved.');
    }
    redirect($self);
}

$st = db()->prepare('SELECT * FROM items WHERE section = ? ORDER BY sort, id');
$st->execute([$section]);
$rows = $st->fetchAll();
$cols = array_keys($def['fields']);

admin_header($def['label'], 'sec-' . $section);
?>
<div class="actions" style="margin-bottom:16px">
  <a class="btn" href="item_edit.php?section=<?= e($section) ?>">+ Add new</a>
</div>

<div class="card table-scroll">
  <?php if (!$rows): ?>
    <p>No items yet.</p>
  <?php else: ?>
  <form method="post" id="sortForm">
    <?= csrf_field() ?><input type="hidden" name="action" value="sort">
  </form>
  <table>
    <thead><tr>
      <th>Order</th>
      <?php if (in_array('media', $cols)): ?><th>Media</th><?php endif; ?>
      <?php foreach ($cols as $c) if ($c !== 'media' && $c !== 'extra') echo '<th>' . e($def['fields'][$c]['label']) . '</th>'; ?>
      <th>Status</th><th></th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><input class="sort" type="number" name="sort[<?= $r['id'] ?>]" value="<?= (int)$r['sort'] ?>" form="sortForm"></td>
        <?php if (in_array('media', $cols)): ?>
          <td class="thumb"><?= $r['media'] ? str_replace(UPLOAD_URL, '../' . UPLOAD_URL, media($r['media'])) : '<span class="badge">demo</span>' ?></td>
        <?php endif; ?>
        <?php foreach ($cols as $c) if ($c !== 'media' && $c !== 'extra') echo '<td>' . e(mb_strimwidth((string)$r[$c], 0, 60, '…')) . '</td>'; ?>
        <td><span class="badge <?= $r['active'] ? 'on' : '' ?>"><?= $r['active'] ? 'Visible' : 'Hidden' ?></span></td>
        <td class="actions" style="justify-content:flex-end">
          <a class="btn small" href="item_edit.php?section=<?= e($section) ?>&id=<?= $r['id'] ?>">Edit</a>
          <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button class="btn small ghost"><?= $r['active'] ? 'Hide' : 'Show' ?></button></form>
          <form method="post" class="inline" onsubmit="return confirm('Delete this item?')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button class="btn small danger">Delete</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p style="margin-bottom:0"><button class="btn small" form="sortForm">Save order</button> <small style="color:#6e6e73">Lower number shows first.</small></p>
  <?php endif; ?>
</div>
<?php admin_footer(); ?>
