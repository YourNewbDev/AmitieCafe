<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']) ?? null;

    try {
        $stmt = $pdo->prepare("INSERT INTO tblcategory (category_name) VALUES (?)");
        $stmt->execute([$category_name]);

        $category_id = $pdo->lastInsertId();

        $_SESSION['success_message'] = "Category successfully added!";

        
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding category: " . $err->getMessage();
        header("Location: ../category/category.php?error=Invalid request");
        exit;
    }

    header("Location: ../manage-categories.php");
    exit;
} 
