<?php
require 'db.php';
$msg = '';

if (isset($_POST['register'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $uname = $_POST['username'];
    $gender = $_POST['gender'];
    $bdate = $_POST['birthdate'];
    $upass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, username, gender, birthdate, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$fname, $lname, $email, $uname, $gender, $bdate, $upass])) {
        $msg = "<p class='success'>Account created! <a href='login.php'>Login here</a></p>";
    } else {
        $msg = "<p class='error'>Registration failed.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Sun Son Solar</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="overlay"></div>
  <div class="quote">
    <h2>“To illuminate a sustainable future by merging clean energy with intelligent design.”</h2>
    <p>— Katherine "Kat" Sinagaraw</p>
  </div>
  <div class="login-box" style="top: 50%; transform: translateY(-50%); max-width: 500px;">
    <h1>Create Account</h1>
    <?php echo $msg; ?>
    <form id="authForm" method="POST">
      <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>First Name</label>
            <input type="text" name="first_name" required>
        </div>
        <div style="flex: 1;">
            <label>Last Name</label>
            <input type="text" name="last_name" required>
        </div>
      </div>
      <label>Email</label>
      <input type="email" name="email" required>
      <div style="display: flex; gap: 10px;">
        <div style="flex: 1;">
            <label>Gender</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div style="flex: 1;">
            <label>Birthdate</label>
            <input type="date" name="birthdate" required>
        </div>
      </div>
      <label>Username</label>
      <input type="text" name="username" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button type="submit" name="register">Sign Up</button>
      <p class="signup">Already have an account? <a href="login.php">Log In</a></p>
    </form>
  </div>
  <script src="script.js"></script>
</body>
</html>