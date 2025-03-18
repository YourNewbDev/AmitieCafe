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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle) ?> | Dashboard</title>
    <link rel="stylesheet" href="/AmitieCafe/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/AmitieCafe/assets/css/custom.css">
    <link rel="icon" href="/AmitieCafe/assets/image/Amitie.png" type="image/x-icon">
    <link rel="stylesheet" href="/AmitieCafe/assets/css/datatables.min.css">
</head>

<body>

    <!-- Bootstrap Navbar (Small Screens) -->
    <nav class="navbar navbar-expand-md d-md-none fixed-top bg-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="/AmitieCafe/assets/image/Amitie-white.png" alt="Amitie Cafe Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
        </div>
    </nav>

    <!-- Bootstrap Offcanvas Sidebar (Small Screens) -->
    <div class="offcanvas offcanvas-start bg-custom" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-center fw-bold text-white">Amitie Cafe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Dashboard</a></li>
                <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Orders</a></li>
                <?php if ($_SESSION['user_role'] === "OWNER") : ?>
                    <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Inventory</a></li>
                    <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Reports</a></li>
                <?php endif; ?>

                <!-- Push these items to the bottom -->
                <li class="nav-item text-center mt-auto">
                    <p class="text-white fw-bold"><?php echo "Online: " . $_SESSION['user_name'] ?></p>
                </li>
                <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Settings</a></li>
                <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Logout</a></li>
            </ul>
        </div>
    </div>


    <!-- Page Layout -->
    <div class="container-fluid d-flex min-vh-100 overflow-hidden">
        <div class="row flex-grow-1 w-100">

            <!-- Left Sidebar (Large Screens) -->
            <aside class="col-md-2 d-none d-md-flex flex-column border-end p-3 bg-custom" style="height: 100vh;">
                <a class="navbar-brand fw-bold text-center mb-3" href="#">
                    <img src="/AmitieCafe/assets/image/Amitie-white.png" alt="Amitie Cafe Logo" height="140">
                </a>
                <nav class="flex-grow-1 d-flex flex-column">
                    <ul class="nav flex-column flex-grow-1">
                        <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>pos.php">POS</a></li>
                        <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Orders</a></li>
                        <?php if ($_SESSION['user_role'] === "OWNER") : ?>
                            <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Dashboard</a></li>
                            <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>admin/manage-products.php">Products</a></li>
                            <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Inventory</a></li>
                            <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Reports</a></li>
                        <?php endif; ?>

                        <!-- Push these items to the bottom -->
                        <p class="text-center text-white fw-bold fs-5 mt-auto"><?php echo "Online: " . $_SESSION['user_name'] ?></p>
                        <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="#">Settings</a></li>
                        <li class="nav-item text-center"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>actions/logout.php">Logout</a></li>
                    </ul>
                </nav>
            </aside>