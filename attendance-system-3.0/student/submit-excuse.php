<?php
session_start();
require_once "class/ExcuseLetter.php";
$excuse = new ExcuseLetter();

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $reason  = $_POST['reason'];

    $attachment = null;
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['attachment']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newName = uniqid('excuse_') . '.' . $extension;
        $targetPath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
            $attachment = $newName; 
        }
    }

    $excuse->submit($user_id, $subject, $reason, $attachment);
    $message = "Excuse letter submitted!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Excuse Letter</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <?php include "../include/studentHeader.php"; ?>

  <div class="max-w-3xl mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Submit Excuse Letter</h2>

    <?php if ($message): ?>
      <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4">
      <div>
        <label for="subject" class="block font-medium mb-1 text-gray-700">Subject:</label>
        <input type="text" id="subject" name="subject"
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
               required>
      </div>

      <div>
        <label for="reason" class="block font-medium mb-1 text-gray-700">Reason:</label>
        <textarea id="reason" name="reason" rows="4"
                  class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                  required></textarea>
      </div>

      <div>
        <label for="attachment" class="block font-medium mb-1 text-gray-700">Attachment:</label>
        <input type="file" id="attachment" name="attachment"
               class="w-full text-gray-700 border border-gray-300 rounded px-3 py-2 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
      </div>

      <button type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
        Submit
      </button>
    </form>
    
    <a href="student-dashboard.php" class="inline-block mt-4 text-blue-700 hover:underline">&larr; Back to Dashboard</a>
  </div>
</body>
</html>
