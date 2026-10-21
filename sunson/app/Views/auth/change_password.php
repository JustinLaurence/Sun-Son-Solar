<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change password | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
  <div class="overlay"></div>
  <div class="login-box">
    <h1>Change password</h1>
    <?php if (session('error')): ?><p class="error"><?= esc(session('error')) ?></p><?php endif; ?>
    <?php if (session('errors')): foreach (session('errors') as $error): ?><p class="error"><?= esc($error) ?></p><?php endforeach; endif; ?>
    <?php if (! session('must_change_password')): ?>
      <p>Choose a new password. It must be at least 12 characters.</p>
    <?php else: ?>
      <p>For account security, choose a new password before continuing.</p>
    <?php endif; ?>
    <form action="<?= site_url('change-password') ?>" method="post">
      <?= csrf_field() ?>
      <?php if (! session('must_change_password')): ?>
        <label>Current password<input type="password" name="current_password" autocomplete="current-password" required></label>
      <?php endif; ?>
      <label>New password<input type="password" name="password" minlength="12" autocomplete="new-password" required></label>
      <label>Confirm new password<input type="password" name="password_confirm" minlength="12" autocomplete="new-password" required></label>
      <button type="submit">Update password</button>
      <?php if (! session('must_change_password')): ?><p class="signup"><a href="<?= site_url('dashboard') ?>">Back to dashboard</a></p><?php endif; ?>
    </form>
  </div>
</body>
</html>
