<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']) ?? null;
    $category = trim($_POST['category_id']) ?? null;
    $subcategory = trim($_POST['subcategory_id']) ?? null;
    $desc = trim($_POST['product_desc']) ?? null;
    $price = $_POST['product_size_price'] ?? null;
    $cost = $_POST['product_size_cost'] ?? null;
    $size = trim($_POST['product_size']) ?? 'Default Size';

    try {
        // 1. Check if product already exists
        $stmt = $pdo->prepare("SELECT product_id FROM tblproduct WHERE product_name = ?");
        $stmt->execute([$name]);
        $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingProduct) {
            // Product exists, reuse product_id
            $product_id = $existingProduct['product_id'];
        } else {
            // Product doesn't exist, insert into tblproduct
            $stmt = $pdo->prepare("INSERT INTO tblproduct (product_name, category_id, subcategory_id, product_desc, created_at, updated_at)
                                   VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$name, $category, $subcategory, $desc]);
            $product_id = $pdo->lastInsertId();
        }

        // 2. Check if the size already exists for this product
        $stmt = $pdo->prepare("SELECT * FROM tblproductsize WHERE product_id = ? AND product_size = ?");
        $stmt->execute([$product_id, $size]);
        $existingSize = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingSize) {
            // 3. Insert new size for this product
            $stmt = $pdo->prepare("INSERT INTO tblproductsize (product_id, product_size, product_size_price, product_size_cost) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $size, $price, $cost]);

            $_SESSION['success_message'] = "Product size successfully added!";
        } else {
            $_SESSION['error_message'] = "This size already exists for the selected product.";
        }

    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding product: " . $err->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}
?>
