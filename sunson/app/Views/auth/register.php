<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
  <div class="overlay"></div>
  <div class="quote">
    <h2>“To illuminate a sustainable future by merging clean energy with intelligent design.”</h2>
    <p>— Katherine "Kat" Sinagaraw</p>
  </div>
  <div class="login-box" style="top: 50%; transform: translateY(-50%); max-width: 520px;">
    <h1>Create Account</h1>
    <?php if (session('success')): ?>
      <p class="success"><?= esc(session('success')) ?> <a href="<?= site_url('login') ?>">Login here</a></p>
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
          </select>
        </div>
      </div>
      <label>Email</label>
      <input type="email" name="email" value="<?= esc(old('email')) ?>" required>
      <label>Phone Number</label>
      <input type="tel" name="phone_number" value="<?= esc(old('phone_number')) ?>" required>
      <label>Address</label>
      <textarea name="address" rows="2" required><?= esc(old('address')) ?></textarea>
      <label>Department (employees only)</label>
      <select name="department">
        <option value="">None / Customer</option>
        <?php foreach (['Administration', 'IT', 'Dispatch', 'Accounting', 'HR', 'Marketing', 'Sales', 'Customer Service'] as $d): ?>
          <option value="<?= esc($d) ?>" <?= old('department') === $d ? 'selected' : '' ?>><?= esc($d) ?></option>
        <?php endforeach; ?>
      </select>
      <label>Username</label>
      <input type="text" name="username" value="<?= esc(old('username')) ?>" required>
      <label>Password</label>
      <input type="password" name="password" minlength="8" required>
      <button type="submit" name="register">Sign Up</button>
      <p class="signup">Already have an account? <a href="<?= site_url('login') ?>">Log In</a></p>
    </form>
  </div>
  <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>
