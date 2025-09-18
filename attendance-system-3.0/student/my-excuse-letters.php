<?php
session_start();
require_once "class/ExcuseLetter.php";
$excuse = new ExcuseLetter();
$letters = $excuse->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Excuse Letters</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <?php include "../include/studentHeader.php"; ?>

  <div class="max-w-5xl mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">My Excuse Letters</h2>

    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Submitted</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($letters)): ?>
            <?php foreach ($letters as $l): ?>
              <?php
                // normalize status
                $status = strtolower(trim($l['status'] ?? 'pending'));

                if ($status === 'approved' || $status === 'accept' || $status === 'accepted') {
                    $badgeClass = 'bg-green-100 text-green-800';
                    $badgeText  = 'Approved';
                } elseif ($status === 'rejected' || $status === 'reject') {
                    $badgeClass = 'bg-red-100 text-red-800';
                    $badgeText  = 'Rejected';
                } else {
                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                    $badgeText  = ucfirst($status); 
                }
              ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($l['subject']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  <?= nl2br(htmlspecialchars($l['reason'])) ?>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full <?= $badgeClass ?>">
                    <?= htmlspecialchars($badgeText) ?>
                  </span>
                </td>

                <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($l['submitted_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-gray-500">No excuse letters found.</td>
            </tr>
          <?php endif; ?>
        </tbody>


      </table>
    </div>

    <a href="student-dashboard.php" class="inline-block mt-4 text-blue-700 hover:underline">&larr; Back to Dashboard</a>
  </div>
  
</body>
</html>
