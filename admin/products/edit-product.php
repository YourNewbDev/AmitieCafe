<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $product_name = trim($_POST['product_name']);
    $subcategory_id = isset($_POST['subcategory_id']) ? (int) $_POST['subcategory_id'] : null;
    $category_id = (int) $_POST['category_id'];
    $product_desc = trim($_POST['product_desc']);
    $product_size_price = $_POST['product_size_price'];
    $product_size_cost = $_POST['product_size_cost'];
    $product_size = trim($_POST['product_size']) ?? null;

    try {
        // Update tblproduct
        $stmtProduct = $pdo->prepare("UPDATE tblproduct
                               SET product_name = ?, subcategory_id = ?,
                               category_id = ?, product_desc = ?,
                               updated_at = NOW()
                               WHERE product_id = ?");
        $stmtProduct->execute([$product_name, $subcategory_id, $category_id, $product_desc, $product_id]);

        // Fetch existing size data
        $stmtOld = $pdo->prepare("SELECT product_size_price, product_size_cost, product_size FROM tblproductsize WHERE product_id = ?");
        $stmtOld->execute([$product_id]);
        $oldProductSize = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$oldProductSize) {
            $_SESSION['error_message'] = "Product size not found.";
            header("Location: ../manage-products.php");
            exit;
        }

        // If form fields are empty, keep old values
        $product_size_price = $product_size_price !== '' ? $product_size_price : $oldProductSize['product_size_price'];
        $product_size_cost = $product_size_cost !== '' ? $product_size_cost : $oldProductSize['product_size_cost'];
        $product_size = $product_size !== '' ? $product_size : $oldProductSize['product_size'];

        // Update tblproductsize
        $stmtProductSize = $pdo->prepare("UPDATE tblproductsize
                                          SET product_size = ?, product_size_price = ?, product_size_cost = ?
                                          WHERE product_id = ?");
        $stmtProductSize->execute([$product_size, $product_size_price, $product_size_cost, $product_id]);

        $_SESSION['success_message'] = "Product successfully updated.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}
