<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['subcategory_id']) && isset($_POST['category_id'])) {
    $subcategory_id = (int) $_POST['subcategory_id'];
    $subcategory_name = trim($_POST['subcategory_name']);
    $category_id = (int) $_POST['category_id'];

    try {
        $sql = "UPDATE tblsubcategory SET subcategory_name = ?, category_id = ? WHERE subcategory_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$subcategory_name, $category_id, $subcategory_id]);
        $_SESSION['success_message'] = "Subcategory successfully updated.";
    } catch (PDOException $err) {
        
        $_SESSION['error_message'] = "Error deleting Subcategory" . $err->getMessage();
    }

    header("Location: ../manage-subcategories.php");
    exit;
}

?>