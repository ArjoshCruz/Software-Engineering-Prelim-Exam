<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../freelancer/index.php");
} 

$categories = $categoryObj->getCategories();   
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = 
        $categoryObj->getSubcategoriesByCategory($cat['category_id']);
}

$filterSubcategory = $_GET['subcategory_id'] ?? null;

if ($filterSubcategory) {
    $getProposals = $proposalObj->getProposalsBySubcategory($filterSubcategory);
} else {
    $getProposals = $proposalObj->getProposals();
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
    <?php include 'includes/navbarCategory.php'; ?>


    <div class="container-fluid">
      <div class="display-4 my-4 text-center">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?>. </span> </div>
      <div class="text-center">
        <?php  
          if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

            if ($_SESSION['status'] == "200") {
              echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
            }

            else {
              echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>"; 
            }

          }
          unset($_SESSION['message']);
          unset($_SESSION['status']);
        ?>
      </div>
          
      <hr>

          <!-- Managing Categories -->
    <div class="row justify-content-center my-4">
      <div class="col-md-8">
        <div class="card shadow-sm border-primary">
          <div class="card-body text-center">
            <h4 class="card-title mb-3">Want to add or edit a Category?</h4>
            <p class="card-text text-muted">
              Manage your marketplace by adding new categories and subcategories here.
            </p>
            <a href="manage_category.php" class="btn btn-primary btn-lg">
              <i class="fas fa-plus"></i> Manage Categories
            </a>
          </div>
        </div>
      </div>
    </div>

      <div class="row justify-content-center">
        <h3 class="h2">-- Here are all proposals --</h3>
        <div class="col-md-12">
          <?php $getProposals = $proposalObj->getProposals(); ?>
          <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 text-center">
                  <h2>
                    <a href="other_profile_view.php?user_id=<?php echo $proposal['proposal_user_id']; ?>">
                      <?php echo htmlspecialchars($proposal['username']); ?>
                    </a>
                  </h2>
                  <img 
                      src="<?php echo '../images/' . htmlspecialchars($proposal['image']); ?>" 
                      class="img-fluid rounded mb-3" 
                      style="max-width: 500px; max-height: 500px; object-fit: cover;" 
                      alt="">
                  <p class="mt-4 h3 mb-4"><?php echo $proposal['description']; ?></p>
                  <!-- Category / Subcategory badges -->
                    <p>
                      <span class="badge badge-primary">
                        <?php echo !empty($proposal['category_name']) ? htmlspecialchars($proposal['category_name']) : '--'; ?>
                      </span>

                      <span class="badge badge-secondary">
                        <?php echo !empty($proposal['subcategory_name']) ? htmlspecialchars($proposal['subcategory_name']) : '--'; ?>
                      </span>
                    </p>
                  <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']);?> PHP</i></h4>
                </div>
                <div class="col-md-6">
                  <div class="card" style="height: 600px;">
                    <div class="card-header"><h2>All Offers</h2></div>
                    <div class="card-body overflow-auto">
                      <h5 class="h5 mb-3 font-italic "><u>Double click to edit your offers and then press enter to save!</u></h5>
                      <?php $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                      <?php foreach ($getOffersByProposalID as $offer) { ?>
                      <div class="offer">
                        <h4><?php echo $offer['username']; ?> <span class="text-primary">( <?php echo $offer['contact_number']; ?> )</span></h4>
                        <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                        <p><?php echo $offer['description']; ?></p>

                        <?php if ($offer['user_id'] == $_SESSION['user_id']) { ?>
                          <form action="core/handleForms.php" method="POST">
                            <div class="form-group">
                              <input type="hidden" class="form-control" value="<?php echo $offer['offer_id']; ?>" name="offer_id" >
                              <input type="submit" class="btn btn-danger" value="Delete" name="deleteOfferBtn">
                            </div>
                          </form>

                          <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none">
                            <div class="form-group">
                              <label for="#">Description</label>
                              <input type="text" class="form-control" value="<?php echo $offer['description']; ?>" name="description">
                              <input type="hidden" class="form-control" value="<?php echo $offer['offer_id']; ?>" name="offer_id" >
                              <input type="submit" class="btn btn-primary form-control" name="updateOfferBtn">
                            </div>
                          </form>
                        <?php } ?>
                        <hr>
                      </div>
                      <?php } ?>
                    </div>
                    
                    <div class="card-footer">
                    <?php 
                      $alreadySubmitted = $offerObj->hasSubmittedOffer($_SESSION['user_id'], $proposal['proposal_id']);
                      if ($alreadySubmitted): 
                    ?>
                      <div class="alert alert-danger mt-3">
                        You have already submitted an offer for this proposal.
                      </div>
                    <?php else: ?>
                      <form action="core/handleForms.php" method="POST">
                        <div class="form-group">
                          <label for="#">Description</label>
                          <input type="text" class="form-control" name="description" required>
                          <input type="hidden" name="proposal_id" value="<?php echo $proposal['proposal_id']; ?>">
                          <input type="submit" class="btn btn-primary float-right mt-4" name="insertOfferBtn"> 
                        </div>
                      </form>
                    <?php endif; ?>
                    </div>

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
       $('.offer').on('dblclick', function (event) {
          var updateOfferForm = $(this).find('.updateOfferForm');
          updateOfferForm.toggleClass('d-none');
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const scrollAmount = 150; // scroll per click
const categoryNav = document.querySelector('.category-navbar ul.nav');

document.querySelector('.arrow-left').addEventListener('click', () => {
    categoryNav.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});

document.querySelector('.arrow-right').addEventListener('click', () => {
    categoryNav.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});
</script>
  </body>
</html>