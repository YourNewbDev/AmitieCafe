<?php

require __DIR__ . '/../../config/database.php';
$required_role = "OWNER";
require __DIR__ . '/../../actions/auth.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['subcategory_id'])) {
    $subcategory_id = (int) $_POST['subcategory_id'];

    try {
        $sql = "DELETE FROM tblsubcategory WHERE subcategory_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$subcategory_id]);
        $_SESSION['success_message'] = "Subcategory succcessfully deleted.";
    } catch (PDOException $err) {
        
        $_SESSION['error_message'] = "Error deleting subcategory" . $err->getMessage();
    }

    header("Location: ../manage-subcategories.php");
    exit;
}

?>