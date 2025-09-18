<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../../core/user.php";             
require_once "../../core/course.php";           
require_once "../class/course-dashboard.php";

$studentId = $_GET['student_id'] ?? null;
$courseId = $_GET['course_id'] ?? null;

if (!$studentId || !$courseId) die("Student ID and Course ID are required.");

$course = new Course((new User())->getConnection());
$dashboard = new CourseDashboard($course->getConnection(), $courseId);

$studentAttendance = $dashboard->getAttendance($studentId);
$studentInfo = $dashboard->getStudent($studentId); // Make a method to get student info
$courseInfo = $dashboard->getCourseInfo();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $studentInfo['name']; ?> - Attendance</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<?php include "../../include/adminHeader.php"; ?>

<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-3xl font-semibold mb-4"><?php echo $studentInfo['name']; ?> - Attendance for <span class="text-blue-700"><?php echo $courseInfo['course_name']; ?></span></h2>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2 border-b">Date</th>
                    <th class="px-4 py-2 border-b">Status</th>
                    <th class="px-4 py-2 border-b">Late?</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($studentAttendance as $a): ?>
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border-b"><?php echo date("Y-m-d H:i", strtotime($a['date'])); ?></td>
                    <td class="px-4 py-2 border-b"><?php echo $a['status']; ?></td>
                    <td class="px-4 py-2 border-b"><?php echo $a['is_late'] ? "Yes" : "No"; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="course-attendance.php?course_id=<?php echo $courseId; ?>" class="inline-block mt-4 text-blue-700 hover:underline">&larr; Back to Course</a>
</div>

</body>
</html>
