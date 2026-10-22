<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Sun Son Solar</title>
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
  <div class="login-box" style="top: 50%; transform: translateY(-50%); max-width: 520px;">
    <h1>Customer Registration</h1>
    <p>Employee accounts are created by Admin or HR.</p>
    <?php if (session('success')): ?>
      <p class="success"><?= esc(session('success')) ?> <a href="<?= site_url('login') ?>">Login here</a></p>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <p class="error"><?= esc(session('error')) ?></p>
    <?php endif; ?>
    <?php if (session('errors')): ?>
      <?php foreach (session('errors') as $e): ?>
        <p class="error"><?= esc($e) ?></p>
      <?php endforeach; ?>
    <?php endif; ?>
    <form id="authForm" action="<?= site_url('register') ?>" method="POST">
      <?= csrf_field() ?>
      <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
          <label>First Name</label>
          <input type="text" name="first_name" value="<?= esc(old('first_name')) ?>" required>
        </div>
        <div style="flex: 1;">
          <label>Middle Name</label>
          <input type="text" name="middle_name" value="<?= esc(old('middle_name')) ?>">
        </div>
      </div>
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= esc(old('last_name')) ?>" required>
      <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
          <label>Birthdate</label>
          <input type="date" name="birthdate" value="<?= esc(old('birthdate')) ?>" required>
        </div>
        <div style="flex: 1;">
          <label>Gender</label>
          <select name="gender" required>
            <option value="Male" <?= old('gender') === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= old('gender') === 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= old('gender') === 'Other' ? 'selected' : '' ?>>Other</option>
            <option value="Prefer not to say" <?= old('gender') === 'Prefer not to say' ? 'selected' : '' ?>>Prefer not to say</option>
          </select>
        </div>
      </div>
      <label>Email</label>
      <input type="email" name="email" value="<?= esc(old('email')) ?>" required>
      <label>Phone Number</label>
      <input type="tel" name="phone_number" value="<?= esc(old('phone_number')) ?>" required>
      <label>Address</label>
      <textarea name="address" rows="2" required><?= esc(old('address')) ?></textarea>
      <label>Username</label>
      <input type="text" name="username" value="<?= esc(old('username')) ?>" required>
      <label>Password</label>
      <input type="password" name="password" minlength="12" required>
      <button type="submit" name="register">Sign Up</button>
      <p class="signup">Already have an account? <a href="<?= site_url('login') ?>">Log In</a></p>
    </form>
  </div>
  <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
