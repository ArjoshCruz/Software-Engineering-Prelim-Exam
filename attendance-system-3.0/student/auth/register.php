<?php
require_once "../../core/user.php"; 

$user = new User();

// fetch courses
$stmt = $user->getConnection()->query("SELECT id, course_name FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $course_id = $_POST['course_id'] ?? null;
    $year_level = $_POST['year_level'];

    // force role to student
    if ($user->register($name, $email, $password, 'student', $course_id, $year_level)) {
        $message = "Student registered successfully.";
    } else {
        $message = "Registration failed. Email might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center ">

<?php include "../../include/headerAuth.php"; ?>

<div class="flex-1 flex items-center justify-center w-full">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Student Registration</h2>

        <?php if ($message): ?>
            <div class="mb-4 p-3 rounded <?php echo str_contains($message,'✅') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block mb-1 font-semibold">Full Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Password</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Course</label>
                <select name="course_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Year Level</label>
                <select name="year_level" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                    <option value="Irregular">Irregular</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 transition">Register</button>

            <p class="text-center text-sm text-gray-600 mt-2">
                Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
            </p>
        </form>
    </div>
</div>

<a href="../../auth.php" class="my-4 inline-block text-blue-600 hover:underline">← Back</a>

</body>
</html>
