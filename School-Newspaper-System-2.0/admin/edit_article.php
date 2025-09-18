<?php
session_start();
require_once 'classes/Article.php';

$articleObj = new Article();
$user_id = $_SESSION['user_id'];
$article_id = $_GET['id'] ?? ($_GET['article_id'] ?? null);

if (!$article_id) {
    die("Invalid article ID.");
}

// Check if writer has permission
if (!$articleObj->hasAcceptedEdit($article_id, $user_id)) {
    die("You are not allowed to edit this article.");
}

// Fetch article
$article = $articleObj->getArticles($article_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = intval($_POST['category_id']);

    $imagePath = null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['article_image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = realpath(__DIR__ . "/../uploads/");
            if ($uploadDir === false) {
                $uploadDir = __DIR__ . "/../uploads/";
                mkdir($uploadDir, 0777, true);
            }
            $uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            $fileName = time() . '_' . basename($_FILES['article_image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['article_image']['tmp_name'], $targetFile)) {
                $imagePath = "uploads/" . $fileName; 
            }
        }
    }

    if (!empty($title) && !empty($content)) {
        $articleObj->updateArticle($article_id, $title, $content, $category_id, $imagePath);
        $_SESSION['success'] = "Article updated successfully!";
        header("Location: shared_articles.php");
        exit;
    } else {
        $error = "All fields are required.";
    }
}

require_once 'classes/Category.php'; 
$categoryObj = new Category();
$categories = $categoryObj->getCategories();

include 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Article</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="card shadow-sm rounded-3">
          <div class="card-header  text-white" style="background-color: #008080;">
            <h4 class="mb-0">Edit Article</h4>
          </div>
          <div class="card-body">

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="title" 
                  name="title" 
                  value="<?= htmlspecialchars($article['title']) ?>" 
                  required
                >
              </div>

              <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea 
                  class="form-control" 
                  id="content" 
                  name="content" 
                  rows="10" 
                  required><?= htmlspecialchars($article['content']) ?></textarea>
              </div>

              <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category_id" required>
                  <option value="">-- Select Category --</option>
                  <?php foreach ($categories as $cat): ?>
                    <option 
                      value="<?= $cat['category_id'] ?>" 
                      <?= $article['category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>


              <div class="mb-3">
                <label class="form-label">Current Image</label>
                <div class="mb-2">
                  <?php if (!empty($article['image_path'])): ?>
                    <img src="../<?= htmlspecialchars($article['image_path']) ?>" alt="Current image" class="img-fluid rounded" style="max-height: 200px;">
                  <?php else: ?>
                    <div class="text-muted">No image uploaded.</div>
                  <?php endif; ?>
                </div>
                <label for="article_image" class="form-label">Replace Image (optional)</label>
                <input type="file" class="form-control" id="article_image" name="article_image" accept="image/*">
                <small class="text-muted">Accepted: JPG, PNG, GIF</small>
              </div>

              <div class="d-flex justify-content-between">
                <a href="shared_articles.php" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn text-white" style="background-color: #008080;">Save Changes</button>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
