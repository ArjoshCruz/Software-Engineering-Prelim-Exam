<?php
session_start();
require_once 'classloader.php';

require_once 'classes/Category.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$catObj = new Category();

// handle add
if (isset($_POST['add_category'])) {
    $catObj->addCategory($_POST['category_name']);
    header("Location: manage_categories.php");
    exit();
}

// handle update
if (isset($_POST['update_category'])) {
    $catObj->updateCategory($_POST['category_id'], $_POST['new_name']);
    header("Location: manage_categories.php");
    exit();
}

// handle delete
if (isset($_GET['delete'])) {
    $catObj->deleteCategory($_GET['delete']);
    header("Location: manage_categories.php");
    exit();
}

$categories = $catObj->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
      .page-header {
      background: linear-gradient(135deg, #103db9ff, #4a8fdeff);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.2);
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      text-align: center;
      margin: 0;
    }

    .page-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      text-align: center;
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

  <div class="page-header">
    <div class="container">
      <h1 class="page-title">Manage Categories</h1>
      <p class="page-subtitle">Create, Update, or Delete a Category</p>
    </div>
  </div>

<div class="container mt-4">
  <h2 class="mb-4">Create Category</h2>

  <!-- Add new category -->
  <form method="POST" class="form-inline mb-4">
    <input type="text" name="category_name" class="form-control mr-2" placeholder="New Category" required>
    <button type="submit" name="add_category" class="btn btn-success">
      <i class="fas fa-plus"></i> Add Category
    </button>
  </form>

  <!-- Category Table -->
  <table class="table table-bordered table-hover">
    <thead class="thead-light">
      <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th style="width:200px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categories as $cat): ?>
      <tr>
        <td><?= $cat['category_id']; ?></td>
        <td>
          <!-- Inline form for editing -->
          <form method="POST" class="form-inline">
            <input type="hidden" name="category_id" value="<?= $cat['category_id']; ?>">
            <input type="text" name="new_name" value="<?= htmlspecialchars($cat['category_name']); ?>" class="form-control mr-2">
            <button type="submit" name="update_category" class="btn btn-primary btn-sm">
              <i class="fas fa-save"></i> Save
            </button>
          </form>
        </td>
        <td>
          <a href="manage_categories.php?delete=<?= $cat['category_id']; ?>" 
             class="btn btn-danger btn-sm"
             onclick="return confirm('Are you sure you want to delete this category?');">
             <i class="fas fa-trash"></i> Delete
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
