<?php
session_start();
require_once 'classloader.php'; 

// fetch categories
$categories = $categoryObj->getCategories();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <title>Manage Categories</title>
</head>
<body class="bg-light">

<?php include 'includes/navbar.php'; ?>

<div class="container py-4">
  <h1 class="mb-4 text-center">Manage Categories</h1>

  <!-- Add Category Form -->
  <div class="card mb-4">
    <div class="card-header">Adding New Category</div>
    <div class="card-body">
      <form action="core/handleForms.php" method="post">
        <div class="form-group">
          <label>Category Name</label>
          <input type="text" name="cat_name" class="form-control" required>
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
      </form>
    </div>
  </div>

  <!-- Add Subcategory Form -->
  <div class="card mb-4">
    <div class="card-header">Adding New Subcategory</div>
    <div class="card-body">
      <form action="core/handleForms.php" method="post">
        <div class="form-group">
          <label>Choose Category</label>
          <select name="category_id" class="form-control" required>
            <option value="">Select</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= $c['category_id']; ?>"><?= htmlspecialchars($c['name']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Subcategory Name</label>
          <input type="text" name="sub_name" class="form-control" required>
        </div>
        <button type="submit" name="add_subcategory" class="btn btn-primary">Add Subcategory</button>
      </form>
    </div>
  </div>

  <!-- Category List -->
  <div class="card">
    <div class="card-header">All Categories & Subcategories</div>
    <div class="card-body">
<?php foreach ($categories as $c): ?>
  <?php 
    $subCategories = $categoryObj->getSubcategoriesByCategory($c['category_id']); 
  ?>
  <div class="mb-3">
    <h5>
      <?= htmlspecialchars($c['name']); ?>
      <a href="core/handleForms.php?delete_cat=<?= $c['category_id']; ?>" 
      class="btn btn-sm btn-danger float-right" 
      onclick="return confirm('Delete this category?')">Delete</a>
    </h5>
    <ul class="list-group">
      <?php foreach ($subCategories as $s): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= htmlspecialchars($s['name']); ?>
          <a href="core/handleForms.php?delete_sub=<?= $s['subcategory_id']; ?>" 
          class="btn btn-sm btn-outline-danger"
          onclick="return confirm('Delete this subcategory?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endforeach; ?>

    </div>
  </div>
</div>
</body>
</html>
