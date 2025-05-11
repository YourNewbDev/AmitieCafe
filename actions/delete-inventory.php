<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inventory_id = $_POST['inventory_id'] ?? null;

    try {
        $stmt = $pdo->prepare("DELETE FROM tblinventory WHERE inventory_id = ?");
        $stmt->execute([$inventory_id]);

        $_SESSION['success_message'] = "Item successfully deleted in the inventory!";

        
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error deleting inventory: " . $err->getMessage();
        header("Location: ../inventory.php");
        exit;
    }

    header("Location: ../inventory.php");
    exit;
} 
