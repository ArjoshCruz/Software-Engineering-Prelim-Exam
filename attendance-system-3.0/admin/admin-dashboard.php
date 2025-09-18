<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../core/user.php";
require_once "../core/course.php";

$user = new User();
$course = new Course($user->getConnection());

$message = "";

// Handle Add or Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = trim($_POST['course_name']);
    $courseId = $_POST['course_id'] ?? null;

    if ($courseId) {
        if ($course->updateCourse($courseId, $courseName)) {
            $message = "Course updated successfully.";
        } else {
            $message = "Failed to update course.";
        }
    } else {
        if ($course->addCourse($courseName)) {
            $message = "Course added successfully.";
        } else {
            $message = "Failed to add course.";
        }
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    if ($course->deleteCourse($deleteId)) {
        $message = "Course deleted successfully.";
    } else {
        $message = "Failed to delete course.";
    }
}

// Fetch courses
$courses = $course->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
function toggleForm(id = null, name = "") {
    const form = document.getElementById('course-form');
    form.classList.toggle('hidden');

    if (id) {
        document.getElementById('course_id').value = id;
        document.getElementById('course_name').value = name;
    } else {
        document.getElementById('course_id').value = "";
        document.getElementById('course_name').value = "";
    }
}
</script>
</head>
<body class="bg-gray-100 min-h-screen">
<?php include "../include/adminHeader.php"; ?>

<div class="max-w-5xl mx-auto my-8 p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
    <p class="mb-4 text-gray-700">Welcome, <span class="font-semibold"><?php echo $_SESSION['name']; ?></span>!</p>

    <?php if ($message): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Course List</h2>
        <button onclick="toggleForm()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Add Course</button>
    </div>

    <form id="course-form" method="POST" action="" class="mb-4 p-4 bg-white rounded shadow hidden">
        <input type="hidden" name="course_id" id="course_id">
        <div class="flex gap-2">
            <input type="text" name="course_name" id="course_name" placeholder="Enter course name" required
                   class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Save</button>
            <button type="button" onclick="toggleForm()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">Cancel</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow overflow-hidden">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Course Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?php echo $c['id']; ?></td>
                    <td class="px-4 py-2">
                        <a href="actions/course-attendance.php?course_id=<?php echo $c['id']; ?>" class="text-blue-600 hover:underline">
                            <?php echo $c['course_name']; ?>
                        </a>
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        <button onclick="toggleForm('<?php echo $c['id']; ?>','<?php echo $c['course_name']; ?>')"
                                class="text-yellow-600 hover:underline">Edit</button>
                        <a href="?delete_id=<?php echo $c['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</div>

</body>
</html>
