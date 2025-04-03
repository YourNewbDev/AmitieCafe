<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subcategory_name = trim($_POST['subcategory_name']) ?? null;
    $category_id = trim($_POST['category_id']) ?? null;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tblsubcategory WHERE subcategory_name = ?");
        $stmt->execute([$subcategory_name]);
        $count = $stmt->fetchColumn();

        if($count > 0) {
            $_SESSION['error_message'] = "Subcategory already exist!";
            header("Location: ../manage-subcategories.php?=error=subcat_exists");
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO tblsubcategory (subcategory_name, category_id) VALUES (?, ?)");
        $stmt->execute([$subcategory_name, $category_id]);

        $subcategory_id = $pdo->lastInsertId();

        $_SESSION['success_message'] = "Subcategory successfully added!";

        
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding category: " . $err->getMessage();
        header("Location: ../manage-subcategories.php");
        exit;
    }

    header("Location: ../manage-subcategories.php");
    exit;
} 
