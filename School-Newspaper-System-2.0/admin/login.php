<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  
  <link rel="stylesheet" href="styles/login.css?v=<?php echo time(); ?>">



  <title>Admin Login</title>
</head>
<body>
  <div class="login-card">
    <h2>Welcome to the Admin Panel</h2>
    <form action="core/handleForms.php" method="POST">
      <?php  
      if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
        $colorClass = $_SESSION['status'] == "200" ? 'alert-success' : 'alert-error';
        echo "<div class='alert-message {$colorClass}'>{$_SESSION['message']}</div>";
      }
      unset($_SESSION['message']);
      unset($_SESSION['status']);
      ?>
      <div class="form-group">
        <label>Username</label>
        <input type="email" class="form-control" name="email">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password">
      </div>
      <input type="submit" class="btn btn-primary mt-3" name="loginUserBtn" value="Login">
    </form>
    <a href="../index.php" class="back-home">← Back to Homepage</a>
  </div>
</body>
</html>
