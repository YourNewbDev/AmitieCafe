<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']) ?? null;
    $category = trim($_POST['category_id']) ?? null;
    $subcategory = trim($_POST['subcategory_id']) ?? null;
    $desc = trim($_POST['product_desc']) ?? null;
    $price = $_POST['product_price'] ?? null;
    $cost = $_POST['product_cost'] ?? null;
    $size = trim($_POST['product_size']) ?? 'Default Size';
    $size_price = $_POST['product_size_price'] ?? 0;

    try {

        $stmt = $pdo->prepare("INSERT INTO tblproduct (product_name, category_id, subcategory_id, product_desc, product_price, product_cost, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $category, $subcategory, $desc, $price, $cost]);

        $product_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("SELECT * FROM tblproductsize WHERE product_id = ? AND product_size = ?");
        $stmt->execute([$product_id, $size]);
        $existingSize = $stmt->fetch();

        if (!$existingSize) {
            $stmt = $pdo->prepare("INSERT INTO tblproductsize (product_id, product_size, product_size_price) VALUES (?, ?, ?)");
            $stmt->execute([$product_id, $size, $size_price]);
        }

        $_SESSION['success_message'] = "Product successfully added!";

    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding product: " . $err->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}

?>
