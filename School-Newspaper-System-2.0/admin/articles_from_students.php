<?php require_once 'classloader.php'; ?>

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

  <title>Pending Articles - Admin Panel</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>


  <style>
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background: #f5f7fa;
      min-height: 100vh;
    }

    .page-header {
      background: linear-gradient(135deg, #103db9ff, #4a8fdeff);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(16, 61, 185, 0.3);
      text-align: center;
    }

    .page-header h2 {
      font-size: 2.5rem;
      font-weight: 700;
    }

    .page-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .article-card {
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      overflow: hidden;
      margin-bottom: 1.5rem;
      background: white;
    }

    .article-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .article-card img {
      border-radius: 16px 16px 0 0;
      max-height: 300px;
      object-fit: cover;
    }

    .badge-status {
      font-size: 0.8rem;
      padding: 0.5em 0.8em;
      border-radius: 20px;
      text-transform: uppercase;
    }

    .badge-pending {
      background: #f87171;
      color: white;
    }

    .badge-active {
      background: #34d399;
      color: white;
    }

    .article-actions {
      margin-top: 1rem;
    }

    .updateArticleForm {
      background: #f1f5f9;
      padding: 1rem;
      border-radius: 12px;
      margin-top: 1rem;
    }

    select.is_active_select {
      max-width: 180px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="page-header">
    <h2>Pending Articles</h2>
    <p>Manage submissions from writers</p>
  </div>

  <div class="container">
    <?php $articles = $articleObj->getArticles(); ?>
    <?php foreach ($articles as $article): ?>
      <div class="article-card p-3">
        <!-- Title & Meta -->
        <h4><?php echo htmlspecialchars($article['title']); ?></h4>
        <p class="text-muted mb-2">
          By <strong><?php echo htmlspecialchars($article['username']); ?></strong> • <?php echo htmlspecialchars($article['created_at']); ?>
        </p>

        <!-- Status Badge -->
        <?php if ($article['is_active'] == 0): ?>
          <span class="badge-status badge-pending">Pending</span>
        <?php else: ?>
          <span class="badge-status badge-active">Active</span>
        <?php endif; ?>

        <!-- Content -->
        <p class="mt-3"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

        <!-- Image -->
        <?php if (!empty($article['image_path'])): ?>
          <div class="text-center my-3">
            <img src="/School-Newspaper-System-2.0/<?php echo htmlspecialchars($article['image_path']); ?>" class="img-fluid">
          </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="article-actions d-flex justify-content-between align-items-center">
          <!-- Delete -->
          <form class="deleteArticleForm d-inline">
            <input type="hidden" name="article_id" value="<?php echo (int)$article['article_id']; ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i> Delete</button>
          </form>

          <!-- Status Update -->
          <form class="updateArticleStatus d-inline">
            <input type="hidden" name="article_id" value="<?php echo (int)$article['article_id']; ?>">
            <select name="is_active" class="form-control form-control-sm is_active_select" article_id="<?php echo (int)$article['article_id']; ?>">
              <option value="">Change Status</option>
              <option value="0" <?php if($article['is_active']==0) echo 'selected'; ?>>Pending</option>
              <option value="1" <?php if($article['is_active']==1) echo 'selected'; ?>>Active</option>
            </select>
          </form>
        </div>

        <!-- Inline Edit Form -->
        <div class="updateArticleForm d-none">
          <h5>Edit Article</h5>
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>">
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($article['content']); ?></textarea>
              <input type="hidden" name="article_id" value="<?php echo (int)$article['article_id']; ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm float-right" name="editArticleBtn">Save Changes</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <script>
    // Toggle inline edit form on double click
    $('.article-card').on('dblclick', function () {
      $(this).find('.updateArticleForm').toggleClass('d-none');
    });

    // Update status via AJAX
    $('.is_active_select').on('change', function (event) {
      event.preventDefault();
      var formData = {
        article_id: $(this).attr('article_id'),
        status: $(this).val(),
        updateArticleVisibility: 1
      };

      if (formData.article_id && formData.status) {
        $.post("core/handleForms.php", formData, function(data) {
          if (data) location.reload();
          else alert("Visibility update failed");
        });
      }
    });
  </script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</body>
</html>
