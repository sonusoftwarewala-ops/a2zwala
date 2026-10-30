<?php
require __DIR__ . '/_bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $st = db()->prepare('SELECT password_hash FROM admins WHERE id = ?');
    $st->execute([$_SESSION['admin_id']]);
    $hash = $st->fetchColumn();
    $new = $_POST['new'] ?? '';

    if (!password_verify($_POST['current'] ?? '', $hash)) flash('Current password is wrong.', 'err');
    elseif (strlen($new) < 6) flash('New password must be at least 6 characters.', 'err');
    elseif ($new !== ($_POST['new2'] ?? '')) flash('New passwords do not match.', 'err');
    else {
        db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        flash('Password changed.');
    }
    redirect('password.php');
}

admin_header('Change password', 'pass');
?>
<form method="post" class="card" style="max-width:460px">
  <?= csrf_field() ?>
  <div class="field"><label>Current password</label><input type="password" name="current" required></div>
  <div class="field"><label>New password</label><input type="password" name="new" required minlength="6"></div>
  <div class="field"><label>Confirm new password</label><input type="password" name="new2" required minlength="6"></div>
  <button class="btn">Update password</button>
</form>
<?php admin_footer(); ?>
