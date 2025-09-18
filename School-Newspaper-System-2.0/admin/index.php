<?php 
require_once 'classloader.php'; 
require_once 'classes/Category.php';

$categoryObj = new Category();
$categories = $categoryObj->getCategories();

?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
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


  <title>Admin Dashboard</title>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="container mt-4">

    <!-- Page Header -->
    <div class="welcome-text">
      Hello <span class="text-success"><?php echo $_SESSION['username']; ?></span>, manage articles below.
    </div>

    <!-- Submit Article Form -->
    <div class="article-form py-5">
      <h4 class="mb-3">Submit a New Article</h4>
      <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
          <input type="text" class="form-control" name="title" placeholder="Enter article title" required>
        </div>
        <div class="form-group">
          <textarea name="description" class="form-control" rows="4" placeholder="Write your article here..." required></textarea>
        </div>
        <div class="form-group">
          <label for="category">Select Category</label>
          <select name="category_id" id="category" class="form-control py-1" required>
            <option value="" class="py-5">-- Select Category --</option>
            <?php foreach($categories as $category): ?>
              <option value="<?php echo $category['category_id']; ?>">
                <?php echo htmlspecialchars($category['category_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <input type="file" class="form-control pb-5" name="article_image">
        </div>
        
        <button type="submit" class="btn btn-success float-right pb-2" name="insertAdminArticleBtn">Publish</button>
      </form>
    </div>


    <!-- Articles Section -->
    <h4 class="mb-3">All Articles</h4>
    <?php $articles = $articleObj->getActiveArticles(); ?>
    <?php foreach ($articles as $article) { ?>
<div class="card shadow-sm mb-3">
  <div class="card-body">
    <!-- Title + Category in one flex row -->
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="mb-0">
        <?php echo $article['title']; ?>
      </h5>
      <span class="badge badge-primary">
        <?php echo htmlspecialchars($article['category_name']); ?>
      </span>
    </div>

    <!-- Author / date -->
    <p class="mt-2 mb-1">
      <small class="text-muted">
        By <strong><?php echo $article['username'] ?></strong> • <?php echo $article['created_at']; ?>
      </small>
    </p>

    <?php if (!empty($article['is_admin']) && $article['is_admin'] == 1) { ?>
      <span class="admin-tag">Message From Admin</span>
    <?php } ?>

    <!-- Content -->
    <p class="mt-2"><?php echo $article['content']; ?></p>

    <!-- Optional image -->
    <?php if (!empty($article['image_path'])) { ?>
      <div class="mt-3 text-center">
        <img src="/School-Newspaper-System-2.0/<?php echo $article['image_path']; ?>" class="img-fluid" style="max-height:300px;">
      </div>
    <?php } ?>

    <!-- Action buttons under content -->
    <div class="mt-3">
      <?php if ($article['author_id'] != $_SESSION['user_id'] && !$articleObj->hasAcceptedEdit($article['article_id'], $_SESSION['user_id'])): ?>
        <form action="core/handleForms.php" method="POST" class="d-inline">
          <input type="hidden" name="article_id" value="<?=$article['article_id']?>">
          <button type="submit" name="requestEditBtn" class="btn btn-sm btn-warning">Request Edit</button>
        </form>
      <?php endif; ?>

      <?php if ($articleObj->hasAcceptedEdit($article['article_id'], $_SESSION['user_id'])): ?>
        <a href="edit_article.php?id=<?= $article['article_id'] ?>" class="btn btn-sm btn-info">Edit</a>
      <?php endif; ?>
      <div class="d-flex justify-content-end">
        <form method="POST" action="core/handleForms.php" class="d-inline"
              onsubmit="return confirm('Are you sure you want to delete this article?');">
          <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
          <button type="submit" name="deleteArticleBtn" class="btn btn-sm btn-outline-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <?php } ?> 

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
