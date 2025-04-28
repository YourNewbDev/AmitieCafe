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
        // Check if the size already exists for the product
        $stmt = $pdo->prepare("SELECT * FROM tblproductsize WHERE product_id = ? AND product_size = ?");
        $stmt->execute([$product_id, $product_size]);
        $existingSize = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingSize) {
            // Size already exists, set an error message
            $_SESSION['error_message'] = "This size already exists for the selected product.";
        } else {
            // Update the tblproduct table with new product details (name, category, description, price, and cost)
            $sql1 = "UPDATE tblproduct SET product_name = ?, category_id = ?, product_desc = ?, updated_at = NOW() WHERE product_id = ?";
            $stmt = $pdo->prepare($sql1);
            $stmt->execute([$product_name, $category_id, $product_desc, $product_id]);

            // If subcategory is set, update it as well
            if ($subcategory_id) {
                $sql2 = "UPDATE tblproduct SET subcategory_id = ? WHERE product_id = ?";
                $stmt = $pdo->prepare($sql2);
                $stmt->execute([$subcategory_id, $product_id]);
            }

            // Update the tblproductsize table with new size, price, and cost for the product size
            $sql3 = "UPDATE tblproductsize SET product_size = ?, product_size_price = ?, product_size_cost = ? WHERE product_id = ? AND product_size";
            $stmt = $pdo->prepare($sql3);
            $stmt->execute([$product_size, $product_size_price, $product_size_cost, $product_id]);

            $_SESSION['success_message'] = "Product successfully updated.";
        }

    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error editing product: " . $err->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}

?>
