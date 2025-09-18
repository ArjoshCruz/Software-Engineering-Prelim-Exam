<?php
session_start();

// If the user is not logged in, redirect to auth.php
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
    <a href="core/logout.php">logout</a>
</body>
</html>
