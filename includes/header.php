<?php

require __DIR__ . "/../config/config.php";
require __DIR__ . '/../actions/auth.php';
include __DIR__ . "/../config/database.php";

if (!isset($pageTitle)) {
  $pageTitle = "Amitie Cafe";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($pageTitle) ?> | Dashboard</title>
  <link rel="stylesheet" href="/AmitieCafe/assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/AmitieCafe/assets/css/custom.css" />
  <link rel="icon" href="/AmitieCafe/assets/image/Amitie.png" type="image/x-icon" />
  <link rel="stylesheet" href="/AmitieCafe/assets/css/datatables.min.css" />
</head>

<body>
  <!-- Mobile Navbar Toggle -->
  <nav class="navbar navbar-dark bg-custom d-lg-none">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="/AmitieCafe/assets/image/Amitie-white.png" height="40" alt="Logo" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenuMobile">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar for Desktop -->
    <div class="bg-custom sidebar d-none d-lg-flex flex-column" style="width: 200px; min-height: 100vh;">
      <div class="d-flex flex-column flex-grow-1 p-3">
        <img src="/AmitieCafe/assets/image/Amitie-white.png" alt="Amitie Cafe Logo" class="mb-3" />
        <ul class="nav flex-column flex-grow-1">
          <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>pos.php">POS</a></li>
          <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>order.php">Orders</a></li>

          <?php if ($_SESSION['user_role'] === "OWNER") : ?>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-categories.php">Categories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-subcategories.php">Subcategories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#">Reports</a>
            </li>
          <?php endif; ?>

          <!-- Push-down items -->
          <li class="nav-item mt-auto">
            <a class="nav-link text-white fw-bold" href="#">Settings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>actions/logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>


    <!-- Offcanvas Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start bg-custom text-white" tabindex="-1" id="sidebarMenuMobile">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>

      <!-- Make this flex-column so bottom items can be pushed down -->
      <div class="offcanvas-body d-flex flex-column">
        <!-- Main nav section (flex-grow-1 to take up remaining space) -->
        <ul class="nav flex-column flex-grow-1">
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>pos.php" data-bs-dismiss="offcanvas">POS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>order.php" data-bs-dismiss="offcanvas">Orders</a>
          </li>

          <?php if ($_SESSION['user_role'] === "OWNER") : ?>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#" data-bs-dismiss="offcanvas">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-categories.php">Categories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-subcategories.php">Subcategories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#" data-bs-dismiss="offcanvas">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fw-bold" href="#" data-bs-dismiss="offcanvas">Reports</a>
            </li>
          <?php endif; ?>
        </ul>

        <!-- Pushed to the bottom -->
        <ul class="nav flex-column mt-auto">
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="#" data-bs-dismiss="offcanvas">Settings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>actions/logout.php" data-bs-dismiss="offcanvas">Logout</a>
          </li>
        </ul>
      </div>
    </div>


  </div>

  <script src="/AmitieCafe/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>