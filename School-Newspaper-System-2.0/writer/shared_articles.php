<?php  
require_once 'classloader.php';

if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit;
}

$user_id = $_SESSION['user_id'];
$sharedArticles = $articleObj->getSharedArticlesForUser($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Shared Articles - Writer Panel</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
			height: 100%;
		}

		.article-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
		}

		.article-image {
			width: 100%;
			height: 200px;
			object-fit: cover;
			border-radius: 16px 16px 0 0;
		}

		.article-content {
			padding: 1.5rem;
		}

		.article-title {
			font-size: 1.3rem;
			font-weight: 600;
			color: #1e293b;
			margin-bottom: 0.75rem;
			line-height: 1.4;
		}

		.article-meta {
			color: #64748b;
			font-size: 0.9rem;
			margin-bottom: 1rem;
		}

		.article-author {
			background: #f1f5f9;
			padding: 0.3rem 0.8rem;
			border-radius: 20px;
			font-size: 0.8rem;
			font-weight: 500;
			color: #475569;
			display: inline-block;
			margin-bottom: 0.5rem;
		}

		.article-date {
			color: #94a3b8;
			font-size: 0.8rem;
			margin-bottom: 1rem;
		}

		.article-preview {
			color: #374151;
			line-height: 1.6;
			margin-bottom: 1.5rem;
			max-height: 4.5rem;
			overflow: hidden;
			position: relative;
		}

		.article-preview::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			height: 1.5rem;
			background: linear-gradient(transparent, white);
		}

		.btn-open {
			background: linear-gradient(135deg, #3b82f6, #1d4ed8);
			color: white;
			border: none;
			border-radius: 8px;
			padding: 0.6rem 1.5rem;
			font-weight: 500;
			transition: all 0.3s ease;
			width: 100%;
		}

		.btn-open:hover {
			background: linear-gradient(135deg, #1d4ed8, #1e40af);
			transform: translateY(-1px);
			color: white;
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

		.shared-badge {
			background: linear-gradient(135deg, #f59e0b, #d97706);
			color: white;
			padding: 0.3rem 0.8rem;
			border-radius: 20px;
			font-size: 0.7rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			position: absolute;
			top: 1rem;
			right: 1rem;
			z-index: 10;
		}

		.article-card-wrapper {
			position: relative;
		}
	</style>
</head>
<body>
	<?php include 'includes/navbar.php'; ?>
	
	<!-- Page Header -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title">Shared Articles</h1>
			<p class="page-subtitle">Articles shared with you for collaboration</p>
		</div>
	</div>

	<div class="container">
		<?php if (empty($sharedArticles)): ?>
			<div class="empty-state">
				<i class="fas fa-share-alt"></i>
				<h3>No shared articles yet</h3>
				<p>No one has shared any articles with you for collaboration.</p>
			</div>
		<?php else: ?>
			<div class="row">
				<?php foreach ($sharedArticles as $article): ?>
					<div class="col-lg-6 col-xl-4 mb-4">
						<div class="article-card-wrapper">
							<div class="article-card">
								<div class="shared-badge">
									<i class="fas fa-share mr-1"></i>Shared
								</div>
								
								<?php if (!empty($article['image_path'])): ?>
									<img class="article-image" src="../<?php echo htmlspecialchars($article['image_path']); ?>" alt="Article image">
								<?php endif; ?>
								
								<div class="article-content">
									<h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
									
									<div class="article-author">
										<i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($article['author_name']); ?>
									</div>
									
									<div class="article-date">
										<i class="fas fa-calendar mr-1"></i>Shared on <?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($article['shared_at']))); ?>
									</div>
									
									<div class="article-preview">
										<?php echo nl2br(htmlspecialchars(substr($article['content'], 0, 200))); ?>
									</div>
									
									<a href="edit_article.php?article_id=<?php echo (int)$article['article_id']; ?>" class="btn btn-open">
										<i class="fas fa-external-link-alt mr-2"></i>Open Article
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


