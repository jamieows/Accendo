<?php
include('includes/db.php');
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $hashed = md5($password);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];

    // ✅ silently redirect to dashboard
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Accendo LMS - Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">

  <div class="login-wrapper">
    <div class="login-box">
      <h1 class="login-logo">ACCENDO</h1>
      <p class="login-subtitle">Where Vision Meets Innovation</p>

      <?php if ($error != ""): ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>

      <form action="" method="POST" class="login-form">
        <div class="input-group">
          <input type="text" name="username" placeholder="USERNAME" required>
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="PASSWORD" required>
        </div>
        <button type="submit" class="login-btn">LOG IN</button>

        <div class="login-options">
          <a href="#">FORGOT PASSWORD?</a>
          <label><input type="checkbox"> REMEMBER ME</label>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
