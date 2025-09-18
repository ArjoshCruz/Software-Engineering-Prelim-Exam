<?php
require_once 'classloader.php'; 

$user_id = $_SESSION['user_id'];
$unreadCount = $notificationObj->countUnread($user_id);
?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<nav class="navbar navbar-expand-lg shadow-sm" 
     style="background: linear-gradient(90deg, #006666, #00b3b3); border-radius: 12px; margin: 10px;">
  <div class="container-fluid">
    <!-- Logo + Brand -->
    <a class="navbar-brand d-flex align-items-center text-white font-weight-bold" href="index.php">
      <i class="fas fa-cogs mr-2"></i> Admin Panel
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="color:white;"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto align-items-lg-center">

        <!-- Home -->
        <li class="nav-item mx-1">
          <a class="nav-link text-white" href="index.php">
            <i class="fas fa-home mr-1"></i> Home
          </a>
        </li>

        <!-- Articles Dropdown -->
        <li class="nav-item dropdown mx-1">
          <a class="nav-link dropdown-toggle text-white" href="#" id="articlesDropdown" role="button" 
             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-newspaper mr-1"></i> Articles Settings
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="articlesDropdown">
            <a class="dropdown-item" href="articles_from_students.php">
              <i class="fas fa-inbox mr-1"></i> Pending Articles
            </a>
            <a class="dropdown-item" href="articles_submitted.php">
              <i class="fas fa-pen-nib mr-1"></i> Articles Submitted
            </a>
            <a class="dropdown-item" href="edit_request.php">
              <i class="fas fa-edit mr-1"></i> Requests
            </a>
            <a class="dropdown-item" href="shared_articles.php">
              <i class="fas fa-share-alt mr-1"></i> Shared Articles
            </a>
            <a class="dropdown-item" href="manage_categories.php">
              <i class="fas fa-gear mr-1"></i> Manage Category
            </a>
          </div>
        </li>

        <!-- Notifications -->
        <li class="nav-item mx-1">
          <a class="nav-link text-white d-flex align-items-center" href="notifications.php">
            <i class="fas fa-bell mr-1"></i> Notifications
            <?php if ($unreadCount > 0): ?>
              <span class="badge badge-pill badge-danger ml-1"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item mx-1">
          <a class="btn btn-sm btn-light font-weight-bold rounded-pill" href="core/handleForms.php?logoutUserBtn=1">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>
