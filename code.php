<?php

session_start();


include "config/database.php";

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
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="icon" href="assets/image/Amitie.png" type="image/x-icon">
</head>

<body>

    <!-- Bootstrap Navbar (Small Screens) -->
    <nav class="navbar navbar-expand-md navbar-light bg-light d-md-none fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="assets/image/Amitie.png" alt="Amitie Cafe Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <?php
    
    if (isset($_SESSION['user_id']) || $_SESSION['user_role'] !== "OWNER" ) {

    }
    
    ?>

    <!-- Bootstrap Offcanvas Sidebar (Small Screens) -->
    <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">Amitie Cafe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Inventory</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Page Layout -->
    <div class="container-fluid d-flex min-vh-100 overflow-hidden">
        <div class="row flex-grow-1 w-100">

            <!-- Left Sidebar (Large Screens) -->
            <aside class="col-md-2 d-none d-md-flex flex-column border-end p-3 bg-light" style="height: 100vh;">
                <a class="navbar-brand fw-bold text-center mb-3" href="#">
                    <img src="assets/image/Amitie.png" alt="Amitie Cafe Logo" height="40">
                </a>
                <nav class="flex-grow-1">
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Inventory</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
                    </ul>
                </nav>
            </aside>