<?php require_once 'writer/classloader.php'; ?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <link rel="stylesheet" href="styles/main.css?v=<?php echo time(); ?>">
  
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #1e3a8a;">
    <div class="d-flex align-items-center">
      <div class="logo-circle mr-3">
        <img src="images/logo.png" alt="Logo">
      </div>
      <h1 class="mb-0 text-white">The Scholars</h1>
    </div>
  </nav>
    
  <main>
    <div class="container-fluid">
      <div class="welcome-message">
        <h2>Welcome to The Scholar's Homepage!</h2>
      </div>
      <div class="row justify-content-center mt-5">
        <div class="col-md-5">
          <div class="writer-panel">
            <div class="panel-header writer-header">
              <h2>WRITER</h2>
            </div>
            <div class="panel-content">
              <img src="https://images.unsplash.com/photo-1577900258307-26411733b430?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid panel-image">
              <p class="panel-text">Content writers create clear, engaging, and informative content that helps businesses communicate their services or products effectively, build brand authority, attract and retain customers, and drive web traffic and conversions.</p>
              <a href="writer/login.php" class="btn btn-login writer-btn">LOGIN</a>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="admin-panel">
            <div class="panel-header admin-header">
              <h2>ADMIN</h2>
            </div>
            <div class="panel-content">
              <img src="https://plus.unsplash.com/premium_photo-1661582394864-ebf82b779eb0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-fluid panel-image">
              <p class="panel-text">Admin writers play a key role in content team development. They are the highest-ranking editorial authority responsible for managing the entire editorial process, and aligning all published material with the publication's overall vision and strategy.</p>
              <a href="admin/login.php" class="btn btn-login admin-btn">LOGIN</a>
            </div>
          </div>
        </div>
      </div>
      <div class="articles-section mt-5">
        <div class="articles-header">
          <h2>Latest Articles</h2>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-8">
          <?php $articles = $articleObj->getActiveArticles(); ?>
            <?php foreach ($articles as $article) { ?>
            <div class="article-card">
              <div class="article-content">
                <?php if ($article['is_admin'] == 1) { ?>
                  <div class="admin-tag">Message from Admin</div>
                <?php } ?>
                <h1 class="article-title"><?php echo $article['title']; ?></h1> 
                <div class="article-meta">
                  <strong><?php echo $article['username'] ?></strong> - <?php echo $article['created_at']; ?>
                </div>
                <p class="article-description"><?php echo $article['content']; ?></p>
                <?php if (!empty($article['image_path']) && $article['image_path'] !== null) { ?>
                  <div class="article-image-container">
                    <img src="<?php echo $article['image_path']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
                  </div>
                <?php } ?>

              </div>
            </div>  
            <?php } ?>   
          </div>
        </div>
      </div>
    </div>
  </main>
  </body>
  </html>