<?php
require_once 'classloader.php';
if (!$userObj->isLoggedIn()) header('Location: login.php');
$author_id = $_SESSION['user_id'];

$requests = $editRequestObj->getRequestsForAuthor($author_id);
?>
<?php include 'includes/navbar.php'; ?>
<div class="container mt-4">
  <h3>Pending Edit Requests</h3>
  <?php if (empty($requests)): ?>
    <p>No pending requests.</p>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($requests as $r): ?>
        <li class="list-group-item">
          <strong><?=$r['requester_name']?></strong> requested to edit
          <em><?=$r['title']?></em>
          <p><?=htmlspecialchars($r['message'])?></p>
          <form action="core/handleForms.php" method="POST" style="display:inline">
            <input type="hidden" name="request_id" value="<?=$r['request_id']?>">
            <button name="acceptEditBtn" class="btn btn-sm btn-success">Accept</button>
          </form>
          <form action="core/handleForms.php" method="POST" style="display:inline">
            <input type="hidden" name="request_id" value="<?=$r['request_id']?>">
            <button name="rejectEditBtn" class="btn btn-sm btn-danger">Reject</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
