<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];

    try {
        $sql = "DELETE FROM tblproduct WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$product_id]);
        $_SESSION['success_message'] = "Product succcessfully deleted.";
    } catch (PDOException $err) {
        
        $_SESSION['error_message'] = "Error deleting product" . $err->getMessage();
    }

    header("Location: ../manage-products.php");
    exit;
}

?>