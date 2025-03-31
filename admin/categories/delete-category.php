<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['category_id'])) {
    $category_id = (int) $_POST['category_id'];

    try {
        $sql = "DELETE FROM tblcategory WHERE category_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$category_id]);
        $_SESSION['success_message'] = "Category successfully deleted.";
    } catch (PDOException $err) {
        
        $_SESSION['error_message'] = "Error deleting category" . $err->getMessage();
    }

    header("Location: ../manage-categories.php");
    exit;
}

?>