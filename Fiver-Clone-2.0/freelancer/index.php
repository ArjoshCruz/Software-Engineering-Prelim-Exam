<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../client/index.php");
} 

$categories = $categoryObj->getCategories();   
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = 
        $categoryObj->getSubcategoriesByCategory($cat['category_id']);
}
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Add Proposal Here!</div>
      <div class="row">
        <div class="col-md-5">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <h1 class="mb-4 mt-4">Add Proposal Here!</h1>

                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                      
                  <!-- Category -->
                  <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                      <option value="">-- Select Category --</option>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Subcategory -->
                  <div class="form-group">
                    <label for="subcategory_id">Subcategory</label>
                    <select id="subcategory_id" name="subcategory_id" class="form-control" required>
                      <option value="">-- Select Subcategory --</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Minimum Price</label>
                    <input type="number" class="form-control" name="min_price" required>
                  </div>
                  

                  <div class="form-group">
                    <label>Max Price</label>
                    <input type="number" class="form-control" name="max_price" required>
                  </div>

                  <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image" required>
                  </div>

                  <input type="submit" class="btn btn-primary float-right mt-4" name="insertNewProposalBtn" value="Add Proposal">
                </div>
              </form>

            </div>
          </div>
        </div>
        <div class="col-md-7">
          <?php $getProposals = $proposalObj->getProposals(); ?>
          <?php foreach ($getProposals as $proposal) { ?>
            <div class="card shadow mt-4 mb-4">
              <div class="card-body">
                <div class="row">
                  <!-- Proposal Info (same style as admin left side) -->
                  <div class="col-md-12 d-flex flex-column align-items-center text-center"> 
                    <h2>
                      <a href="other_profile_view.php?user_id=<?php echo $proposal['user_id'] ?>">
                        <?php echo htmlspecialchars($proposal['username']); ?>
                      </a>
                    </h2>

                    <img 
                      src="<?php echo '../images/' . htmlspecialchars($proposal['image']); ?>" 
                      class="img-fluid rounded mb-3" 
                      style="max-width: 500px; max-height: 500px; object-fit: cover;" 
                      alt="">

                    <p class="mt-4 h3 mb-4"><?php echo htmlspecialchars($proposal['description']); ?></p>

                    
                    <p class="mb-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>

                    <!-- Category / Subcategory badges -->
                    <p>
                      <span class="badge badge-primary">
                        <?php echo !empty($proposal['category_name']) ? htmlspecialchars($proposal['category_name']) : '--'; ?>
                      </span>

                      <span class="badge badge-secondary">
                        <?php echo !empty($proposal['subcategory_name']) ? htmlspecialchars($proposal['subcategory_name']) : '--'; ?>
                      </span>
                    </p>

                    <h4>
                      <i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i>
                    </h4>

                    <!-- “Check out services” button -->
                    <div class="mt-3">
                      <a href="proposal_view.php?id=<?php echo $proposal['proposal_id']; ?>" class="btn btn-outline-primary">
                        Check out services
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>

      </div>
    </div>
  
<script>
const subcategories = <?= json_encode($allSubcategories); ?>;

$('#category_id').on('change', function() {
    let catId = $(this).val();
    let $sub = $('#subcategory_id');
    $sub.html('<option value="">-- Select Subcategory --</option>');
    if (subcategories[catId]) {
        subcategories[catId].forEach(function(sc) {
            $sub.append(
              `<option value="${sc.subcategory_id}">${sc.name}</option>`
            );
        });
    }
});
</script>
  </body>
</html>