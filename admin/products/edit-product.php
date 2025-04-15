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
    $product_price = $_POST['product_price'];
    $product_cost = $_POST['product_cost'];
    $product_size = trim($_POST['product_size']) ?? null;


    try {
        if($subcategory_id) {
            $sql1 = "UPDATE tblproduct SET product_name = ?, subcategory_id = ?, category_id = ?, product_desc = ?, product_price = ?, product_cost = ?, updated_at = NOW()  WHERE product_id = ?";
            $stmt = $pdo->prepare($sql1);
            $stmt -> execute([$product_name, $subcategory_id, $category_id, $product_desc, $product_price, $product_cost, $product_id]);
        }

        $sql1 = "UPDATE tblproduct SET product_name = ?, category_id = ?, product_desc = ?, product_price = ?, product_cost = ?, updated_at = NOW()  WHERE product_id = ?";
        $stmt = $pdo->prepare($sql1);
        $stmt -> execute([$product_name, $category_id, $product_desc, $product_price, $product_cost, $product_id]);

        $sql2 = "UPDATE tblproductsize SET product_size = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql2);
        $stmt -> execute([$product_size, $product_id]);

        $_SESSION['success_message'] = "Product successfully updated.";

    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error editing product" . $err->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}

?>