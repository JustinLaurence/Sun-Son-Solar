<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
  <div class="overlay"></div>
  <div class="login-box">
    <h1>Hello, <?= esc($firstName) ?></h1>
    <p>Role: <?= esc($role) ?></p>
    <p class="signup"><a href="<?= site_url('logout') ?>">Log out</a></p>
  </div>
</body>
</html>
