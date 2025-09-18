<?php
session_start();
require_once "../../core/user.php"; // include User class

$user = new User();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        // Check role
        if ($_SESSION['user_role'] === 'admin') {
            header("Location: ../admin-dashboard.php");
            exit;
        } else {
            session_destroy();
            $message = "Access denied. Only admins can log in here.";
        }
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center ">

<!-- Header -->
<?php
include "../../include/headerAuth.php";
?>

<div class="flex-1 flex items-center justify-center w-full">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-yellow-600">Admin Login</h2>

        <?php if ($message): ?>
            <div class="mb-4 p-3 rounded <?php echo str_contains($message,'❌') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-500 transition">Login</button>

            <p class="text-center text-sm text-gray-600 mt-2">
                Don't have an account? <a href="register.php" class="text-yellow-600 hover:underline">Register</a>
            </p>
        </form>
    </div>
</div>

<a href="../../auth.php" class="my-4 inline-block text-blue-600 hover:underline">← Back</a>

</body>
</html>
