<?php 
require_once 'classloader.php';
require_once 'classes/Category.php';

$categoryObj = new Category();
$categories = $categoryObj->getCategories();

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

  <link rel="stylesheet" href="styles/index.css?v=<?php echo time(); ?>">

  <title>Writers Dashboard</title>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container">
    <div class="text-center welcome-text">
      Hello there and welcome, <span class="text-success"><?php echo $_SESSION['username']; ?></span> <br>
    </div>

    <!-- Article Submission Form -->
<form action="core/handleForms.php" method="POST" enctype="multipart/form-data" class="article-form">
  <h2 class="mb-3">What's on your mind?</h2>
  <div class="form-group">
    <input type="text" class="form-control" name="title" placeholder="Input title here">
  </div>
  <div class="form-group">
    <textarea name="description" class="form-control" rows="4" placeholder="Submit an article!"></textarea>
  </div>
  
  <!-- Category Dropdown -->
  <div class="form-group">
    <label for="category">Select Category</label>
    <select name="category_id" id="category" class="form-control py-1">
      <option value="" disabled selected>-- Choose a category --</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?= htmlspecialchars($category['category_id']) ?>">
          <?= htmlspecialchars($category['category_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  
  <div class="form-group">
    <input type="file" class="form-control py-1" name="article_image">
  </div>

  <button type="submit" class="btn btn-primary btn-block" name="insertArticleBtn">Publish Article</button>
</form>


    <div class="text-center welcome-text">
      Here are all the articles
    </div>

    <!-- Articles -->
    <div class="row justify-content-center">
      <div class="col-md-8">
        <?php $articles = $articleObj->getActiveArticles(); ?>
        <?php foreach ($articles as $article) { ?>
        <div class="card mt-4">
          <div class="card-body">

            <!-- Admin Tag -->
            <?php if ($article['is_admin'] == 1): ?>
              <span class="badge badge-danger mb-2">Message from Admin</span>
            <?php endif; ?>

            <!-- Title -->
            <h4 class="card-title mb-1">
              <?= htmlspecialchars($article['title']) ?>
            </h4>

            <!-- Meta (author + date + category) -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <small class="text-muted">
                <strong><?= htmlspecialchars($article['username']) ?></strong>
                • <?= htmlspecialchars($article['created_at']) ?>
              </small>
              <?php if (!empty($article['category_name'])): ?>
                <span class="badge badge-info"><?= htmlspecialchars($article['category_name']) ?></span>
              <?php endif; ?>
            </div>

            <!-- Content -->
            <p class="card-text"><?= nl2br(htmlspecialchars($article['content'])) ?></p>

            <!-- Image -->
            <?php if (!empty($article['image_path'])): ?>
              <img src="/School-Newspaper-System-2.0/<?= htmlspecialchars($article['image_path']) ?>"
                  class="img-fluid d-block mx-auto mb-3 rounded"
                  alt="Article Image">
            <?php endif; ?>


          <!-- Buttons -->
          <div class="d-flex justify-content-end">
            <?php 
            // Request Edit only if it's not your article and you don't have rights yet
            if (
                isset($_SESSION['user_id']) &&
                $article['author_id'] != $_SESSION['user_id'] &&
                !$articleObj->hasAcceptedEdit($article['article_id'], $_SESSION['user_id'])
            ): ?>
              <form action="core/handleForms.php" method="POST" class="mr-2">
                <input type="hidden" name="article_id" value="<?= (int)$article['article_id'] ?>">
                <button type="submit" name="requestEditBtn" class="btn btn-sm btn-warning">
                  Request Edit
                </button>
              </form>
            <?php endif; ?>

            <?php 
            // Edit Article only if you are NOT the author but you have accepted edit
            if (
                isset($_SESSION['user_id']) &&
                $article['author_id'] != $_SESSION['user_id'] && 
                $articleObj->hasAcceptedEdit($article['article_id'], $_SESSION['user_id'])
            ): ?>
              <a href="edit_article.php?id=<?= (int)$article['article_id'] ?>" class="btn btn-sm btn-primary">
                Edit Article
              </a>
            <?php endif; ?>
          </div>



          </div>
        </div>
  
        <?php } ?> 
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
