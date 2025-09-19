<?php require_once 'classloader.php'; ?>
<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit;
}

// fetch all proposals from DB
$getProposals = $proposalObj->getProposals();

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
    <div class="display-4 text-center">
      Admin Dashboard – Welcome <span class="text-success"><?php echo $_SESSION['username']; ?></span>
    </div>

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


    <div class="my-5 row justify-content-center">
      <div class="col-md-12">

        <div class="display-4 text-center">
          -- All Proposals and Their Offers --
        </div>

        <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <div class="row">
                <!-- Proposal Info -->
                <div class="col-md-6 d-flex flex-column align-items-center text-center"> 
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


                  <p>
                    <!-- Category badge -->
                    <span class="badge badge-primary">
                      <?php echo !empty($proposal['category_name']) ? htmlspecialchars($proposal['category_name']) : '--'; ?>
                    </span>

                    <!-- Subcategory badge -->
                    <span class="badge badge-secondary">
                      <?php echo !empty($proposal['subcategory_name']) ? htmlspecialchars($proposal['subcategory_name']) : '--'; ?>
                    </span>
                  </p>

                  
                  <h4>
                    <i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i>
                  </h4>
                </div>

                <!-- Offers for this Proposal -->
                <div class="col-md-6">
                  <div class="card" style="height: 600px;">
                    <div class="card-header"><h2>All Offers</h2></div>
                    <div class="card-body overflow-auto">
                      <?php 
                      $getOffersByProposalID = $offerObj->getOffersByProposalID($proposal['proposal_id']); 
                      foreach ($getOffersByProposalID as $offer) { ?>
                        <div class="offer">
                          <h4><?php echo htmlspecialchars($offer['username']); ?> 
                            <span class="text-primary">( <?php echo htmlspecialchars($offer['contact_number']); ?> )</span>
                          </h4>
                          <small><i><?php echo $offer['offer_date_added']; ?></i></small>
                          <p><?php echo htmlspecialchars($offer['description']); ?></p>

                          <hr>
                        </div>
                      <?php } ?>
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
</body>
</html>
