<?php
session_start();
require_once "class/student-class.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$dashboard = new StudentDashboard($_SESSION['user_id']);
$studentInfo = $dashboard->getStudentInfo();
$attendanceGrouped = $dashboard->getAttendanceHistoryGrouped();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Attendance History</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<?php include "../include/studentHeader.php"; ?>

<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-2 text-gray-800">Attendance History for <?php echo $studentInfo['name']; ?></h1>
    <div class="mb-4 text-gray-700">
        <p>Course: <span class="font-semibold"><?php echo $studentInfo['course_name']; ?></span></p>
        <p>Year Level: <span class="font-semibold"><?php echo $studentInfo['year_level']; ?></span></p>
    </div>

    <hr class="my-4">

    <?php if (empty($attendanceGrouped)): ?>
        <p class="text-gray-600">No attendance records found.</p>
    <?php else: ?>
        <?php foreach ($attendanceGrouped as $date => $records): ?>
            <h3 class="text-xl font-semibold mt-6 mb-2 text-gray-800"><?php echo $date; ?></h3>
            <div class="overflow-x-auto bg-white rounded shadow mb-4">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2 border-b">Time</th>
                            <th class="px-4 py-2 border-b">Status</th>
                            <th class="px-4 py-2 border-b">Late?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $rec): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b"><?php echo date("H:i", strtotime($rec['date'])); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo $rec['status']; ?></td>
                            <td class="px-4 py-2 border-b">
                                <?php echo ($rec['status'] === 'Absent') ? '' : ($rec['is_late'] ? 'Yes' : 'No'); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="student-dashboard.php" class="inline-block mt-4 text-blue-700 hover:underline">&larr; Back to Dashboard</a>
</div>

</body>
</html>
