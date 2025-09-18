<?php require_once 'classloader.php'; ?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <link rel="stylesheet" href="styles/register.css?v=<?php echo time(); ?>">


  <title>Register - Writers</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg p-4">
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
          <div class="card-header">
            <h2>Register as a Writer</h2>
            <p class="text-muted">Create your account to get started</p>
          </div>
          <form action="core/handleForms.php" method="POST">
            <div class="card-body">
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Choose a username" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter a password" required>
              </div>
              <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="Re-enter password" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block mt-3" name="insertNewUserBtn">Register</button>
              <p>Already have an account? <a href="login.php">Login here</a></p>
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
