<?php 
require_once 'classloader.php'; 
if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
}

$proposalObj = new Proposal();
$offerObj = new Offer();

$categories = $categoryObj->getCategories();   
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = $categoryObj->getSubcategoriesByCategory($cat['category_id']);
}

$filterSubcategory = $_GET['subcategory_id'] ?? null;
$filterCategory = $_GET['category_id'] ?? null;

if ($filterSubcategory) {
    $getProposals = $proposalObj->getProposalsBySubcategory($filterSubcategory);
} elseif ($filterCategory) {
    $getProposals = $proposalObj->getProposalsByCategory($filterCategory);
} else {
    $getProposals = $proposalObj->getProposals();
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <style>
      body { font-family: "Arial"; }
  </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>
<?php include 'includes/navbarCategory.php'; ?>

<div class="container-fluid">
    <div class="display-4 text-center mt-4">Filtered Proposals</div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <?php if (!empty($getProposals)): ?>
                <?php foreach ($getProposals as $proposal): ?>
                    <div class="card shadow mt-4 mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>
                                        <a href="other_profile_view.php?user_id=<?= $proposal['user_id'] ?>">
                                            <?= htmlspecialchars($proposal['username']); ?>
                                        </a>
                                    </h2>
                                    <img src="<?= '../images/' . htmlspecialchars($proposal['image']); ?>" 
                                         class="img-fluid rounded mb-3" 
                                         style="max-width:500px; max-height:500px; object-fit:cover;" 
                                         alt="">
                                    <p class="mt-4 mb-4"><?= htmlspecialchars($proposal['description']); ?></p>

                                    <!-- Category / Subcategory badges -->
                                    <p>
                                        <span class="badge badge-primary">
                                            <?= !empty($proposal['category_name']) ? htmlspecialchars($proposal['category_name']) : '--'; ?>
                                        </span>
                                        <span class="badge badge-secondary">
                                            <?= !empty($proposal['subcategory_name']) ? htmlspecialchars($proposal['subcategory_name']) : '--'; ?>
                                        </span>
                                    </p>

                                    <h4>
                                        <i><?= number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i>
                                    </h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="card" style="height:600px;">
                                        <div class="card-header"><h2>All Offers</h2></div>
                                        <div class="card-body overflow-auto">
                                            
                                            <h5 class="h5 mb-3 font-italic "><u>Double click to edit your offers and then press enter to save!</u></h5>
                                            <?php $offers = $offerObj->getOffersByProposalID($proposal['proposal_id']); ?>
                                            <?php foreach ($offers as $offer): ?>
                                                <div class="offer">
                                                    <h4>
                                                        <?= htmlspecialchars($offer['username']); ?> 
                                                        <span class="text-primary">( <?= htmlspecialchars($offer['contact_number']); ?> )</span>
                                                    </h4>
                                                    <small><i><?= $offer['offer_date_added']; ?></i></small>
                                                    <p><?= htmlspecialchars($offer['description']); ?></p>

                                                    <?php if ($offer['user_id'] == $_SESSION['user_id']): ?>
                                                        <form action="core/handleForms.php" method="POST">
                                                            <input type="hidden" name="offer_id" value="<?= $offer['offer_id']; ?>">
                                                            <input type="submit" class="btn btn-danger" name="deleteOfferBtn" value="Delete">
                                                        </form>

                                                        <form action="core/handleForms.php" method="POST" class="updateOfferForm d-none">
                                                            <input type="text" class="form-control" name="description" value="<?= htmlspecialchars($offer['description']); ?>">
                                                            <input type="hidden" name="offer_id" value="<?= $offer['offer_id']; ?>">
                                                            <input type="submit" class="btn btn-primary mt-2" name="updateOfferBtn" value="Update">
                                                        </form>
                                                    <?php endif; ?>
                                                    <hr>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="card-footer">
                                            <?php if ($offerObj->hasSubmittedOffer($_SESSION['user_id'], $proposal['proposal_id'])): ?>
                                                <div class="alert alert-danger mt-3">
                                                    You have already submitted an offer for this proposal.
                                                </div>
                                            <?php else: ?>
                                                <form action="core/handleForms.php" method="POST">
                                                    <input type="text" class="form-control" name="description" required>
                                                    <input type="hidden" name="proposal_id" value="<?= $proposal['proposal_id']; ?>">
                                                    <input type="submit" class="btn btn-primary mt-2 float-right" name="insertOfferBtn" value="Submit Offer">
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center mt-4">No proposals found for this subcategory.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    $('.offer').on('dblclick', function () {
        $(this).find('.updateOfferForm').toggleClass('d-none');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
