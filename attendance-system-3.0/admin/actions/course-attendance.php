<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../../core/user.php";             
require_once "../../core/course.php";           
require_once "../class/course-dashboard.php";

// Initialize course object first
$course = new Course((new User())->getConnection());

$courseId = $_GET['course_id'] ?? null;
if (!$courseId) die("Course ID is required.");

// Pass the PDO connection via the getter to CourseDashboard
$dashboard = new CourseDashboard($course->getConnection(), $courseId);

$courseInfo = $dashboard->getCourseInfo();
$students = $dashboard->getStudents();

$yearLevels = ['1st Year','2nd Year','3rd Year','4th Year','Irregular'];
$currentYear = $_GET['year_level'] ?? '1st Year';
$students = $dashboard->getStudentsByYear($currentYear);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course Attendance - <?php echo $courseInfo['course_name']; ?></title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<!-- Header -->
<?php include "../../include/adminHeader.php"; ?>

<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-3xl font-semibold mb-4">Attendance for <span class="text-blue-700"><?php echo $courseInfo['course_name']; ?></span></h2>

    <div class="overflow-x-auto bg-white rounded shadow">
        
    <div class="flex gap-2 mb-4">
    <?php foreach ($yearLevels as $level): ?>
        <a href="?course_id=<?php echo $courseId; ?>&year_level=<?php echo urlencode($level); ?>"
        class="px-3 py-1 rounded <?php echo $currentYear === $level ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'; ?>">
        <?php echo $level; ?>
        </a>
    <?php endforeach; ?>
    </div>
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-4 py-2 border-b">Student Name</th>
                    <th class="px-4 py-2 border-b">Email</th>
                    <th class="px-4 py-2 border-b">Year Level</th>
                    <th class="px-4 py-2 border-b">Attendance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border-b"><?php echo $student['name']; ?></td>
                    <td class="px-4 py-2 border-b"><?php echo $student['email']; ?></td>
                    <td class="px-4 py-2 border-b"><?php echo $student['year_level']; ?></td>
                    <td class="px-4 py-2 border-b">
                        <a href="student-attendance.php?student_id=<?php echo $student['id']; ?>&course_id=<?php echo $courseId; ?>" class="text-blue-600 hover:underline">
                            View Full Attendance
                        </a>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="../admin-dashboard.php" class="inline-block mt-4 text-blue-700 hover:underline">&larr; Back to Dashboard</a>
</div>

</body>
</html>
