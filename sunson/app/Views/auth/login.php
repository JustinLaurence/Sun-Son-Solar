<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
  <div class="overlay"></div>
  <div class="quote">
    <h2>“To illuminate a sustainable future by merging clean energy with intelligent design.”</h2>
    <p>— Katherine "Kat" Sinagaraw</p>
  </div>
  <div class="login-box">
    <h1>Welcome back</h1>
    <?php if (session('success')): ?>
      <p class="success"><?= esc(session('success')) ?></p>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <p class="error"><?= esc(session('error')) ?></p>
    <?php endif; ?>
    <form id="authForm" action="<?= site_url('login') ?>" method="POST">
      <?= csrf_field() ?>
      <label>Username</label>
      <input type="text" name="username" value="<?= esc(old('username')) ?>" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div class="options">
        <span>Customer and employee sign-in</span>
      </div>
      <button type="submit" name="login">Log In</button>
      <p class="signup">Customers can <a href="<?= site_url('register') ?>">create an account</a>. Employee accounts are created by Admin or HR.</p>
    </form>
  </div>
  <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
