<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accendo LMS - Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">
  <div class="login-wrapper">
    <div class="login-box">
      <h1 class="login-logo">ACCENDO</h1>
      <p class="login-subtitle">Where Vision Meets Innovation</p>

      <form action="dashboard.php" method="POST" class="login-form">
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
