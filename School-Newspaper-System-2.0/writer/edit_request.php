<?php 
require_once 'classloader.php';

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id']; // logged-in author

// get pending requests for this author
$requests = $articleObj->getRequestsForAuthor($user_id);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Requests - Writer Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background: linear-gradient(135deg, #f9fafb, #eef2ff);
      min-height: 100vh;
    }

    .page-header {
      background: linear-gradient(135deg, #10b981, #4ade80);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2);
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      text-align: center;
    }

    .page-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      text-align: center;
      margin-top: 0.5rem;
    }

    .request-card {
      background: white;
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 1.5rem;
      transition: all 0.3s ease;
      overflow: hidden;
      position: relative;
    }

    .request-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .request-content {
      padding: 1.5rem;
    }

    .request-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }

    .requester-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      flex-shrink: 0;
    }

    .requester-avatar i {
      color: white;
      font-size: 1.3rem;
    }

    .requester-info {
      flex: 1;
    }

    .requester-name {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 0.25rem;
    }

    .request-time {
      color: #64748b;
      font-size: 0.85rem;
    }

    .article-title {
      background: #f1f5f9;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      border-left: 4px solid #3b82f6;
    }

    .article-title-text {
      font-size: 1rem;
      font-weight: 500;
      color: #1e293b;
      margin: 0;
    }

    .request-message {
      background: #f8fafc;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      border-left: 4px solid #10b981;
    }

    .message-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }

    .message-text {
      color: #374151;
      line-height: 1.6;
      margin: 0;
    }

    .request-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: flex-end;
    }

    .btn-accept {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-accept:hover {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-1px);
      color: white;
    }

    .btn-reject {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.6rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-reject:hover {
      background: linear-gradient(135deg, #dc2626, #b91c1c);
      transform: translateY(-1px);
      color: white;
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #64748b;
    }

    .empty-state i {
      font-size: 4rem;
      color: #cbd5e1;
      margin-bottom: 1rem;
    }

    .pending-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: white;
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .request-stats {
      background: white;
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      color: #f59e0b;
      margin-bottom: 0.5rem;
    }

    .stats-label {
      color: #64748b;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  
  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <h1 class="page-title">Edit Requests</h1>
      <p class="page-subtitle">Manage collaboration requests for your articles</p>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Request Stats -->
        <div class="request-stats">
          <div class="stats-number"><?= count($requests) ?></div>
          <div class="stats-label">Pending Requests</div>
        </div>

        <?php if (empty($requests)): ?>
          <div class="empty-state">
            <i class="fas fa-handshake"></i>
            <h3>No pending requests</h3>
            <p>You don't have any pending edit requests at the moment.</p>
          </div>
        <?php else: ?>
          <?php foreach ($requests as $req): ?>
            <div class="request-card">
              <div class="pending-badge">
                <i class="fas fa-clock mr-1"></i>Pending
              </div>
              
              <div class="request-content">
                <div class="request-header">
                  <div class="requester-avatar">
                    <i class="fas fa-user"></i>
                  </div>
                  
                  <div class="requester-info">
                    <div class="requester-name"><?= htmlspecialchars($req['requester_name']); ?></div>
                    <div class="request-time">
                      <i class="fas fa-calendar mr-1"></i>
                      <?= date('M d, Y \a\t h:i A', strtotime($req['created_at'])); ?>
                    </div>
                  </div>
                </div>

                <div class="article-title">
                  <p class="article-title-text">
                    <i class="fas fa-file-alt mr-2"></i>
                    "<?= htmlspecialchars($req['title']); ?>"
                  </p>
                </div>

                <?php if (!empty($req['message'])): ?>
                  <div class="request-message">
                    <div class="message-label">Message from requester</div>
                    <p class="message-text"><?= htmlspecialchars($req['message']); ?></p>
                  </div>
                <?php endif; ?>

                <div class="request-actions">
                  <form action="core/handleForms.php" method="POST" class="d-inline">
                    <input type="hidden" name="request_id" value="<?= $req['request_id']; ?>">
                    <button type="submit" name="rejectRequestBtn" class="btn btn-reject">
                      <i class="fas fa-times mr-1"></i>Reject
                    </button>
                  </form>
                  
                  <form action="core/handleForms.php" method="POST" class="d-inline">
                    <input type="hidden" name="request_id" value="<?= $req['request_id']; ?>">
                    <button type="submit" name="acceptRequestBtn" class="btn btn-accept">
                      <i class="fas fa-check mr-1"></i>Accept
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
