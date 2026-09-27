<?php
session_start();
require 'db.php'; 
$err = '';

if (isset($_POST['login'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    }
    $err = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Sun Son Solar</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="overlay"></div>
  <div class="quote">
    <h2>“To illuminate a sustainable future by merging clean energy with intelligent design.”</h2>
    <p>— Katherine "Kat" Sinagaraw</p>
  </div>
  <div class="login-box">
    <h1>Welcome back</h1>
    <?php if($err) echo "<p class='error'>$err</p>"; ?>
    <form id="authForm" method="POST">
      <label>Username</label>
      <input type="text" name="username" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <div class="options">
        <label><input type="checkbox"> Remember me</label>
        <a href="#">Forgot password?</a>
      </div>
      <button type="submit" name="login">Log In</button>
      <p class="signup">Don't have an account? <a href="register.php">Sign Up</a></p>
    </form>
  </div>
  <script src="script.js"></script>
</body>
</html>