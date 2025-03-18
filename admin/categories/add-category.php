<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']) ?? null;
    $redirect_page = $_POST['redirect_page'] ?? 'category.php';

    try {
        $stmt = $pdo->prepare("SELECT category_id FROM tblcategory WHERE category_name = ?");
        $stmt->execute([$category_name]);
        $existing_category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_category) {
            header("Location: ../$redirect_page?error=Category already exists");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO tblcategory (category_name) VALUES (?)");
        $stmt->execute([$category_name]);
        $category_id = $pdo->lastInsertId();

        if ($redirect_page === 'add-product.php') {
            header("Location: ../products/add-product.php?category_id=" . $category_id);
        } else {
            header("Location: ../products/add-product.php?success=Category added successfully");
        }
        exit;
    } catch (PDOException $err) {
        header("Location: ../$redirect_page?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../category/category.php?error=Invalid request");
    exit;
}
