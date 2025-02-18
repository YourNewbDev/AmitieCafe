<?php include "includes/header.php" ?>

<div class="container-fluid d-flex flex-column min-vh-100">
    <div class="row flex-grow-1">

        <!-- Sidebar Toggle Button (Visible on Small Screens) -->
        <nav class="navbar navbar-expand-md navbar-light bg-light d-md-none">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span class="fw-bold">Menu</span>
            </div>
        </nav>

        <!-- Left Sidebar (Now uses Bootstrap Offcanvas for better mobile behavior) -->
        <div class="offcanvas offcanvas-start text-white" tabindex="-1" id="sidebarMenu">
            <div class="offcanvas-header" style="background-color: #273852;">
                <h5 class="offcanvas-title text-white">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3" style="background-color: #273852;">
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Orders</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Inventory</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Reports</a></li>
                    <li class="nav-item"><a href="#" class="nav-link text-white">Settings</a></li>
                </ul>
            </div>
        </div>

        <!-- Sidebar for Desktop -->
        <aside class="col-md-2 d-none d-md-block p-3 border-end text-white" style="background-color: #273852;">
            <h5 class="fw-bold">Menu</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Orders</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Inventory</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Reports</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Settings</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="col-12 col-md-7 p-4">
            <h2>Bleu Bean POS</h2>
            <p>Welcome to the Point-of-Sale System.</p>
        </main>

        <!-- Right Sidebar -->
        <aside class="col-12 col-md-3 p-3 border-start">
            <h5 class="fw-bold text-center">CURRENT SALE</h5>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Cofsadsadsadsadsadfee <span class="badge bg-primary rounded-pill">₱150.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Muffin <span class="badge bg-primary rounded-pill">₱80.00</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Espresso <span class="badge bg-primary rounded-pill">₱154.00</span>
                </li>
            </ul>
            <hr>
            <div class="d-flex justify-content-between">
                <h6>Total:</h6>
                <h6>₱384.00</h6>
            </div>
            <button class="btn btn-success w-100 mt-3">Complete Sale</button>
        </aside>

    </div>
</div>






