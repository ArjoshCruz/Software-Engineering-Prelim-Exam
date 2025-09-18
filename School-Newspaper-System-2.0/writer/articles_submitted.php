<?php 

require_once 'classloader.php'; 

require_once 'classes/Category.php';
$categoryObj = new Category();
$categories = $categoryObj->getCategories();

$articles = $articleObj->getArticlesByUserID($_SESSION['user_id']);
?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
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
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
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

      .article-card {
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        overflow: hidden;
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

      .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .status-pending {
        background: #fef3c7;
        color: #d97706;
      }

      .status-active {
        background: #d1fae5;
        color: #059669;
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

      .floating-add {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #10b981, #4ade80);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
        z-index: 1000;
        cursor: pointer;
      }

      .floating-add:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
      }

      .floating-add i {
        color: white;
        font-size: 24px;
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Page Header -->
    <div class="page-header">
      <div class="container">
        <h1 class="page-title">My Articles</h1>
        <p class="page-subtitle">Manage and edit your submitted articles</p>
      </div>
    </div>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
          
          <?php if (empty($articles)): ?>
            <div class="empty-state">
              <i class="fas fa-file-alt"></i>
              <h3>No articles yet</h3>
              <p>You haven't submitted any articles. Start writing your first article!</p>
            </div>
          <?php else: ?>
            <?php foreach ($articles as $article) { ?>
            <div class="article-card articleCard">
              <div class="article-header">
                <h2 class="article-title"><?php echo htmlspecialchars($article['title']); ?> (Double click to edit)</h2>
                <div class="article-meta">
                  <i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($article['username']); ?> • 
                  <i class="fas fa-calendar mr-1"></i><?php echo $article['created_at']; ?>
                </div>
                <?php if ($article['is_active'] == 0) { ?>
                  <span class="status-badge status-pending">
                    <i class="fas fa-clock mr-1"></i>Pending Review
                  </span>
                <?php } else { ?>
                  <span class="status-badge status-active">
                    <i class="fas fa-check-circle mr-1"></i>Published
                  </span>
                <?php } ?>
              </div>

              <div class="article-content">
                <p class="article-text"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
                
                <?php if (!empty($article['image_path'])) { ?>
                  <img src="/School-Newspaper-System-2.0/<?php echo $article['image_path']; ?>" 
                       class="img-fluid article-image" alt="Article Image">
                <?php } ?>
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
                  <h4 class="mb-3">
                    <i class="fas fa-edit mr-2"></i>Edit Article
                  </h4>
                  <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="font-weight-semibold">Title</label>
                      <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
                    </div>

                    <div class="form-group">
                      <label class="font-weight-semibold">Content</label>
                      <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($article['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                      <label class="font-weight-semibold">Category</label>
                      <select name="category_id" class="form-control py-1">
                        <option value="" <?= is_null($article['category_id']) ? 'selected' : '' ?>>-- Choose category --</option>
                        <?php foreach ($categories as $cat): ?>
                          <option value="<?= htmlspecialchars($cat['category_id']) ?>"
                            <?= $cat['category_id'] === $article['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>

                    </div>


                    <div class="form-group">
                      <label class="font-weight-semibold">Update Image (optional)</label>
                      <?php if (!empty($article['image_path'])) { ?>
                        <div class="mb-2">
                          <img src="/School-Newspaper-System-2.0/<?php echo $article['image_path']; ?>" 
                               alt="Current Image" class="img-fluid" style="max-height:150px; border-radius: 8px;">
                          <small class="text-muted d-block">Current image</small>
                        </div>
                      <?php } ?>
                      <input type="file" class="form-control" name="article_image" accept="image/*">
                    </div>

                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <div class="text-right">
                      <button type="button" class="btn btn-secondary mr-2" onclick="toggleEdit(<?php echo $article['article_id']; ?>)">
                        Cancel
                      </button>
                      <button type="submit" class="btn btn-save" name="editArticleBtn">
                        <i class="fas fa-save mr-1"></i>Save Changes
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>  
            <?php } ?>
          <?php endif; ?>
        </div>
      </div>
    </div>


    <script>
      // Toggle edit form visibility
      function toggleEdit(articleId) {
        var editForm = $('#edit-form-' + articleId);
        editForm.toggleClass('d-none');
        
        // Scroll to edit form if opening
        if (!editForm.hasClass('d-none')) {
          editForm[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }

      // Double click to edit (legacy support)
      $('.articleCard').on('dblclick', function (event) {
        var articleId = $(this).find('.article_id').val();
        toggleEdit(articleId);
      });

      // Delete article with modern confirmation
      $('.deleteArticleForm').on('submit', function (event) {
        event.preventDefault();
        var formData = {
          article_id: $(this).find('.article_id').val(),
          deleteArticleBtn: 1
        }
        
        // Modern confirmation dialog
        if (confirm("⚠️ Are you sure you want to delete this article?\n\nThis action cannot be undone.")) {
          var deleteBtn = $(this).find('.deleteArticleBtn');
          var originalText = deleteBtn.html();
          
          // Show loading state
          deleteBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Deleting...');
          deleteBtn.prop('disabled', true);
          
          $.ajax({
            type: "POST",
            url: "core/handleForms.php",
            data: formData,
            success: function (data) {
              if (data) {
                // Show success message
                deleteBtn.html('<i class="fas fa-check mr-1"></i>Deleted!');
                setTimeout(function() {
                  location.reload();
                }, 1000);
              } else {
                // Show error message
                deleteBtn.html('<i class="fas fa-exclamation-triangle mr-1"></i>Failed');
                deleteBtn.prop('disabled', false);
                setTimeout(function() {
                  deleteBtn.html(originalText);
                }, 2000);
              }
            },
            error: function() {
              deleteBtn.html('<i class="fas fa-exclamation-triangle mr-1"></i>Error');
              deleteBtn.prop('disabled', false);
              setTimeout(function() {
                deleteBtn.html(originalText);
              }, 2000);
            }
          });
        }
      });

      // Add smooth scrolling for better UX
      $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
          event.preventDefault();
          $('html, body').stop().animate({
            scrollTop: target.offset().top - 100
          }, 1000);
        }
      });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  </body>
</html>