<?php
require_once "../../core/user.php"; // include your User class

$user = new User();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // force role to admin (no course_id, no year_level)
    if ($user->register($name, $email, $password, 'admin')) {
        $message = "Admin registered successfully.";
    } else {
        $message = "Registration failed. Email might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Registration</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center">

<!-- Header -->
<?php
include "../../include/headerAuth.php";
?>

<div class="flex-1 flex items-center justify-center w-full">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-yellow-600">Admin Registration</h2>

        <?php if ($message): ?>
            <div class="mb-6 p-3 rounded <?php echo str_contains($message,'✅') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Full Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" required>
            </div>

            <button type="submit" class="w-full bg-yellow-400 text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-500 transition">Register</button>

            <p class="text-center text-sm text-gray-600 mt-4">
                Already have an account? <a href="login.php" class="text-yellow-600 hover:underline">Login</a>
            </p>
        </form>
    </div>
</div>

<a href="../../auth.php" class="my-4 inline-block text-blue-600 hover:underline">← Back</a>
</body>
</html>
