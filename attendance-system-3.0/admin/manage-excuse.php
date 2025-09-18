<?php
session_start();
require_once "class/ManageExcuseLetter.php";

$manageExcuse = new ManageExcuseLetter();

// Approving or rejecting an excuse letter
if (isset($_GET['action'], $_GET['id'])) {
    $manageExcuse->handleAction($_GET['action'], $_GET['id']);
}

// Filtering by course
$course_id = $_GET['course_id'] ?? null;
$letters = $manageExcuse->getLetterByCourse($course_id);
$courses = $manageExcuse->getCourses();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Excuse Letters</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <?php include "../include/adminHeader.php"; ?>

  <div class="max-w-7xl mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Excuse Letters</h2>

    <!-- Filter -->
    <form class="mb-6 flex flex-wrap items-center gap-4">
      <label for="course_id" class="text-gray-700 font-medium">Filter by Program:</label>
      <select name="course_id" id="course_id" class="px-3 py-2 border rounded-md shadow-sm">
        <option value="">All</option>
        <?php foreach ($courses as $row): ?>
          <option value="<?= $row['id'] ?>" <?= ($course_id==$row['id'])?'selected':''; ?>>
            <?= htmlspecialchars($row['course_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filter</button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($letters)): ?>
            <?php foreach ($letters as $l): ?>
              <?php
                // Row + badge colors depending on status
                switch (strtolower($l['status'])) {
                    case 'approved':
                        $rowColor = 'bg-green-50';
                        $badgeColor = 'bg-green-100 text-green-800';
                        break;
                    case 'rejected':
                        $rowColor = 'bg-red-50';
                        $badgeColor = 'bg-red-100 text-red-800';
                        break;
                    default: // pending or others
                        $rowColor = 'bg-yellow-50';
                        $badgeColor = 'bg-yellow-100 text-yellow-800';
                        break;
                }
              ?>
              <tr class="<?= $rowColor ?> hover:bg-opacity-80">
                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($l['name']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($l['course_name']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($l['year_level']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($l['subject']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  <?= nl2br(htmlspecialchars($l['reason'])) ?>
                </td>
                <td class="px-6 py-4 text-sm">
                  <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $badgeColor ?>">
                    <?= htmlspecialchars(ucfirst($l['status'])) ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-sm">
                  <a href="?action=approve&id=<?= $l['id'] ?>" 
                     class="inline-block px-3 py-2 mb-2 bg-green-600 text-white rounded hover:bg-green-700">Approve</a>
                  <a href="?action=reject&id=<?= $l['id'] ?>" 
                     class="inline-block px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Reject</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="px-6 py-4 text-center text-gray-500">No excuse letters found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
