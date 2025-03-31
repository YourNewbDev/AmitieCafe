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

    <!-- Always Visible Navbar (Burger Menu + Logo Left, Admin Info Right) -->
    <nav class="navbar navbar-expand-md bg-custom p-0 sticky-top">
        <div class="container-fluid">
            <!-- Left: Burger Menu + Logo -->
            <div class="d-flex align-items-center">
                <button class="btn text-white me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                    <img src="/AmitieCafe/assets/image/burger-menu.svg" alt="Amitie Cafe Logo" height="25">
                </button>
                <a class="navbar-brand text-white me-3" href="#"> Amitie Cafe</a>
            </div>

            <!-- Right: Online Admin, Settings, Logout -->
            <div class="ms-auto d-flex align-items-center">
                <span class="text-white fw-bold me-3"><?php echo $_SESSION['user_name']; ?></span>
                <a class="nav-link text-white me-3" href="#">Settings</a>
                <a class="nav-link text-white me-3" href="<?php echo BASE_URL; ?>actions/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar (Hidden Until Burger Clicked) -->
    <div class="offcanvas offcanvas-start bg-custom" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <img src="/AmitieCafe/assets/image/Amitie-white.png" alt="Amitie Cafe Logo" height="60">
            <button type="button" class="btn-close text-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>pos.php">POS</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">Orders</a></li>

                <?php if ($_SESSION['user_role'] === "OWNER") : ?>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">Dashboard</a></li>
                    <li class="nav-item">
                        <button class="btn text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Maintenance
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item fw-bold" href="<?php echo BASE_URL; ?>admin/manage-products.php">Products</a></li>
                            <li><a class="dropdown-item fw-bold" href="<?php echo BASE_URL; ?>admin/manage-categories.php">Categories</a></li>
                            <li><a class="dropdown-item fw-bold" href="<?php echo BASE_URL; ?>admin/manage-subcategories.php">Subcategories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">Inventory</a></li>
                    <li class="nav-item"><a class="nav-link text-white fw-bold" href="#">Reports</a></li>
                <?php endif; ?>

                <!-- Push these items to the bottom -->
                <li class="nav-item mt-auto"><a class="nav-link text-white fw-bold" href="#">Settings</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-bold" href="<?php echo BASE_URL; ?>actions/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>