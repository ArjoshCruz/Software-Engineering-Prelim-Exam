<?php
$categories = $categoryObj->getCategories();
$allSubcategories = [];
foreach ($categories as $cat) {
    $allSubcategories[$cat['category_id']] = $categoryObj->getSubcategoriesByCategory($cat['category_id']);
}
?>
<div class="category-navbar bg-primary py-2">
  <ul class="nav pl-5">
      <?php foreach ($categories as $cat): ?>
          <li class="nav-item dropdown mx-2">
              <!-- Dropdown toggle -->
              <a class="nav-link dropdown-toggle text-white" href="#" id="catDropdown<?= $cat['category_id'] ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?= htmlspecialchars($cat['name']); ?>
              </a>

              <!-- Dropdown menu -->
              <div class="dropdown-menu" aria-labelledby="catDropdown<?= $cat['category_id'] ?>">
                  <!-- Category itself clickable -->
                  <a class="dropdown-item font-weight-bold" href="filterIndex.php?category_id=<?= $cat['category_id'] ?>">
                      View All <?= htmlspecialchars($cat['name']); ?>
                  </a>
                  <div class="dropdown-divider"></div>

                  <!-- Subcategories -->
                  <?php
                  $subs = $allSubcategories[$cat['category_id']] ?? [];
                  foreach ($subs as $sub):
                  ?>
                      <a class="dropdown-item" href="filterIndex.php?subcategory_id=<?= $sub['subcategory_id'] ?>">
                          <?= htmlspecialchars($sub['name']); ?>
                      </a>
                  <?php endforeach; ?>
              </div>
          </li>
      <?php endforeach; ?>
  </ul>
</div>
