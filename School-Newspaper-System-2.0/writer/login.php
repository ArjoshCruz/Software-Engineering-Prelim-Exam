<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="styles/login.css?v=<?php echo time(); ?>">

    <title>Login - Writers Dashboard</title>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg p-4">
            <div class="card-header">
              <h2>Welcome to Writers Dashboard</h2>
              <p class="text-muted">Login to continue</p>
            </div>
            <form action="core/handleForms.php" method="POST">
              <div class="card-body">
                <?php  
                if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                  if ($_SESSION['status'] == "200") {
                    echo "<div class='alert-message alert-success'>{$_SESSION['message']}</div>";
                  } else {
                    echo "<div class='alert-message alert-error'>{$_SESSION['message']}</div>"; 
                  }
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
                }
                ?>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3" name="loginUserBtn">Login</button>
                <p>Don't have an account yet? <a href="register.php">Register here</a></p>
              </div>
            </form>
          </div>
        <div class="text-center mt-3">
          <a href="../index.php" class="btn btn-outline-primary">← Back to Homepage</a>
        </div>
        </div>
      </div>
    </div>
  </body>
</html>
