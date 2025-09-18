<?php
session_start();
require_once "class/student-class.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$dashboard = new StudentDashboard($_SESSION['user_id']);
$studentInfo = $dashboard->getStudentInfo();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $dateTime = !empty($_POST['date_time']) ? $_POST['date_time'] : null;
    $message = $dashboard->fileAttendance($status, $dateTime);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<?php include "../include/studentHeader.php"; ?>

<div class="max-w-4xl mx-auto p-6">
  <h1 class="text-3xl font-bold mb-2 text-gray-800">Student Dashboard</h1>
  <div class="mb-4 text-gray-700">
    <p>Welcome, <span class="font-semibold"><?php echo $studentInfo['name']; ?></span></p>
    <p>Course: <span class="font-semibold"><?php echo $studentInfo['course_name']; ?></span></p>
    <p>Year Level: <span class="font-semibold"><?php echo $studentInfo['year_level']; ?></span></p>
  </div>

  <hr class="my-4">

  <!-- Attendance Form -->
  <h3 class="text-2xl font-semibold mb-2 text-gray-800">Daily Attendance</h3>
  <?php if ($message): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?php echo $message; ?></div>
  <?php endif; ?>
  <form method="POST" action="" class="bg-white p-6 rounded shadow-md space-y-4">
    <div>
      <label for="status" class="block font-medium mb-1">Status:</label>
      <select id="status" name="status" required
              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="present">Present</option>
        <option value="absent">Absent</option>
      </select>
    </div>

    <div>
      <label for="date_time" class="block font-medium mb-1">Date & Time:</label>
      <div class="flex gap-2 items-center">
        <input type="datetime-local" id="date_time" name="date_time"
               class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="button" onclick="useNow()"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-2 rounded transition">
          Use Today's Date & Time
        </button>
      </div>
    </div>

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">File Attendance</button>
  </form>

  <hr class="my-6">

  <!-- Submit Excuse Letter -->
  <h3 class="text-2xl font-semibold mb-4 text-gray-800">Submit an Excuse Letter</h3>
  <a href="submit-excuse.php"
     class="block bg-white p-6 rounded shadow-md hover:shadow-lg transition text-blue-600 font-medium">
    Go now →
  </a>

  <hr class="my-6">

</div>


<script>
function useNow() {
  const now = new Date();

  // create local datetime string in the correct format for datetime-local
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');

  const localDatetime = `${year}-${month}-${day}T${hours}:${minutes}`;
  document.getElementById('date_time').value = localDatetime;
}
</script>

</body>
</html>
