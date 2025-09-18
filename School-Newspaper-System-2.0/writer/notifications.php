<?php
require_once 'classloader.php';

$user_id = $_SESSION['user_id'];

// mark all as read once the page is opened
$notificationObj->markAllAsRead($user_id);

// then fetch all notifications
$notifications = $notificationObj->getUserNotifications($user_id);

include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - Writer Panel</title>
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

    .notification-card {
      background: white;
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 1rem;
      transition: all 0.3s ease;
      overflow: hidden;
      position: relative;
    }

    .notification-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .notification-content {
      padding: 1.5rem;
    }

    .notification-message {
      font-size: 1rem;
      color: #1e293b;
      line-height: 1.6;
      margin-bottom: 0.75rem;
    }

    .notification-time {
      color: #64748b;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
    }

    .notification-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      flex-shrink: 0;
    }

    .notification-icon i {
      color: white;
      font-size: 1.1rem;
    }

    .notification-item {
      display: flex;
      align-items: flex-start;
    }

    .notification-details {
      flex: 1;
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

    .mark-read-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: linear-gradient(135deg, #10b981, #4ade80);
      color: white;
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .notification-stats {
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
      color: #10b981;
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
  <!-- Page Header -->
  <div class="page-header">
    <div class="container">
      <h1 class="page-title">Notifications</h1>
      <p class="page-subtitle">Stay updated with your latest activities</p>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Notification Stats -->
        <div class="notification-stats">
          <div class="stats-number"><?= count($notifications) ?></div>
          <div class="stats-label">Total Notifications</div>
        </div>

        <?php if (count($notifications) > 0): ?>
          <?php foreach ($notifications as $notif): ?>
            <div class="notification-card">
              <div class="mark-read-badge">
                <i class="fas fa-check mr-1"></i>Read
              </div>
              
              <div class="notification-content">
                <div class="notification-item">
                  <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                  </div>
                  
                  <div class="notification-details">
                    <div class="notification-message">
                      <?= htmlspecialchars($notif['message']) ?>
                    </div>
                    
                    <div class="notification-time">
                      <i class="fas fa-clock mr-1"></i>
                      <?= date('M d, Y \a\t h:i A', strtotime($notif['created_at'])) ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <h3>No notifications yet</h3>
            <p>You're all caught up! New notifications will appear here when you receive them.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
