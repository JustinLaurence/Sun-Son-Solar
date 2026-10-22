<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
  <div class="overlay"></div>
  <a class="home-link" href="<?= site_url('/') ?>" aria-label="Back to homepage">
    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
      <path d="M19 12H5m0 0 7 7m-7-7 7-7"></path>
    </svg>
  </a>
  <div class="quote">
    <h2>Power Your Future with Solar</h2>
    <p>Make the switch to dependable, thoughtfully designed solar energy with a team that is with you at every step.</p>
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
