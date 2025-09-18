<?php 
require_once 'classloader.php'; 
require_once 'classes/Category.php';
$categoryObj = new Category();
$categories = $categoryObj->getCategories();

?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
  exit;
}  
?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background: linear-gradient(135deg, #f9fafb, #eef2ff);
      min-height: 100vh;
    }

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

    .article-card {
      background: white;
      border: none;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 2rem;
      transition: all 0.3s ease;
      overflow: hidden;
      cursor: pointer;
    }

    .article-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .article-header {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      padding: 1.5rem;
      border-bottom: 1px solid #e2e8f0;
    }

    .article-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0 0 0.5rem 0;
    }

    .article-meta {
      color: #64748b;
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .article-content {
      padding: 1.5rem;
    }

    .article-text {
      color: #374151;
      line-height: 1.6;
      margin-bottom: 1rem;
    }

    .article-image {
      border-radius: 12px;
      margin: 1rem 0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .article-actions {
      padding: 1rem 1.5rem;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-modern {
      border-radius: 8px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
      border: none;
    }

    .btn-edit {
      background: #3b82f6;
      color: white;
    }

    .btn-edit:hover {
      background: #2563eb;
      transform: translateY(-1px);
    }

    .btn-delete {
      background: #ef4444;
      color: white;
    }

    .btn-delete:hover {
      background: #dc2626;
      transform: translateY(-1px);
    }

    .edit-form {
      background: #f1f5f9;
      border-radius: 12px;
      padding: 1.5rem;
      margin-top: 1rem;
      border: 2px solid #e2e8f0;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid #d1d5db;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-save {
      background: #10b981;
      color: white;
      border-radius: 8px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-save:hover {
      background: #059669;
      transform: translateY(-1px);
    }

    .modal-content {
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="page-header">
    <div class="container">
      <h1 class="page-title">Manage Articles</h1>
      <p class="page-subtitle">Edit or delete any article submitted by writers</p>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>

        <?php if (empty($articles)): ?>
          <div class="text-center text-muted mt-5">
            <i class="fas fa-file-alt" style="font-size: 4rem;"></i>
            <h3>No articles yet</h3>
          </div>
        <?php else: ?>
          <?php foreach ($articles as $article): ?>
            <div class="article-card articleCard">
              <div class="article-header">
                <h2 class="article-title"><?php echo htmlspecialchars($article['title']); ?> (Double click to edit)</h2>
                <div class="article-meta">
                  <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($article['username']); ?> • 
                  <i class="fas fa-calendar mr-1"></i><?php echo $article['created_at']; ?>
                </div>
              </div>

              <div class="article-content">
                <p class="article-text"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                <?php if (!empty($article['image_path'])): ?>
                  <img src="/School-Newspaper-System-2.0/<?php echo $article['image_path']; ?>" class="img-fluid article-image" alt="">
                <?php endif; ?>
              </div>

              <div class="article-actions">
                <button class="btn btn-modern btn-edit" onclick="toggleEdit(<?php echo $article['article_id']; ?>)">
                  <i class="fas fa-edit mr-1"></i>Edit Article
                </button>
                <form class="deleteArticleForm d-inline">
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                  <button type="submit" class="btn btn-modern btn-delete deleteArticleBtn">
                    <i class="fas fa-trash mr-1"></i>Delete
                  </button>
                </form>
              </div>

              <div class="updateArticleForm d-none" id="edit-form-<?php echo $article['article_id']; ?>">
                <div class="edit-form">
                  <h4 class="mb-3"><i class="fas fa-edit mr-2"></i>Edit Article</h4>
<form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label>Title</label>
    <input type="text" class="form-control" name="title"
           value="<?php echo htmlspecialchars($article['title']); ?>" required>
  </div>

  <div class="form-group">
    <label>Content</label>
    <textarea name="description" class="form-control" rows="6" required>
      <?php echo htmlspecialchars($article['content']); ?>
    </textarea>
  </div>

  <div class="form-group">
    <label>Category</label>
    <select name="category_id" class="form-control py-1" required>
      <option value="">Select Category</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?php echo $cat['category_id']; ?>"
          <?php echo ($article['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($cat['category_name']); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- existing image upload code -->

  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">

  <div class="text-right">
    <button type="button" class="btn btn-secondary mr-2"
            onclick="toggleEdit(<?php echo $article['article_id']; ?>)">Cancel</button>
    <button type="submit" class="btn btn-save" name="editArticleBtn">
      <i class="fas fa-save mr-1"></i>Save Changes
    </button>
  </div>
</form>

                </div>
              </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal<?php echo $article['article_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $article['article_id']; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel<?php echo $article['article_id']; ?>">Confirm Delete</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to delete "<strong><?php echo $article['title']; ?></strong>"?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="core/handleForms.php" class="d-inline">
                      <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                      <button type="submit" name="deleteArticleBtn" class="btn btn-danger btn-sm">Yes, Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle edit form visibility
    function toggleEdit(articleId) {
      var editForm = $('#edit-form-' + articleId);
      editForm.toggleClass('d-none');
      if (!editForm.hasClass('d-none')) {
        editForm[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

    // Double click to edit
    $('.articleCard').on('dblclick', function () {
      var articleId = $(this).find('.article_id').val();
      toggleEdit(articleId);
    });

    // Delete with confirmation
    $('.deleteArticleForm').on('submit', function (event) {
      event.preventDefault();
      var formData = { article_id: $(this).find('.article_id').val(), deleteArticleBtn: 1 };
      if (confirm("Are you sure you want to delete this article?\nThis action cannot be undone.")) {
        var deleteBtn = $(this).find('.deleteArticleBtn');
        var originalText = deleteBtn.html();
        deleteBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Deleting...');
        deleteBtn.prop('disabled', true);
        $.ajax({
          type: "POST",
          url: "core/handleForms.php",
          data: formData,
          success: function (data) {
            if (data) {
              deleteBtn.html('<i class="fas fa-check mr-1"></i>Deleted!');
              setTimeout(function() { location.reload(); }, 1000);
            } else {
              deleteBtn.html('<i class="fas fa-exclamation-triangle mr-1"></i>Failed');
              deleteBtn.prop('disabled', false);
              setTimeout(function(){ deleteBtn.html(originalText); }, 2000);
            }
          },
          error: function() {
            deleteBtn.html('<i class="fas fa-exclamation-triangle mr-1"></i>Error');
            deleteBtn.prop('disabled', false);
            setTimeout(function(){ deleteBtn.html(originalText); }, 2000);
          }
        });
      }
    });
  </script>
</body>
</html>
