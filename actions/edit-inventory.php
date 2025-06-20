<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../actions/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inventory_id = $_POST['inventory_id'];
    $inventory_name = trim($_POST['inventory_name']) ?? null;
    $inventory_amount = (int) $_POST['inventory_amount'] ?? null;
    $inventory_total_unit_cost = (float) $_POST['inventory_total_unit_cost'] ?? null;
    $inventory_unit_cost = (float) $inventory_total_unit_cost / $inventory_amount;
    $supplier_name = trim($_POST['supplier_name']) ?? null;
    $supplier_contact = trim($_POST['supplier_contact']) ?? null;

    $inventory_amount_with_measurement = (int) $_POST['inventory_amount'] . " " . $_POST['inventory_measure'] ?? null;

    try {
        $stmt = $pdo->prepare("UPDATE tblinventory SET inventory_name = ?,
                                    inventory_amount = ?, inventory_unit_cost = ?,
                                    inventory_total_unit_cost = ?, supplier_name = ?,
                                    supplier_contact = ?
                                    WHERE inventory_id = ?");
        $stmt->execute([$inventory_name, $inventory_amount_with_measurement,
                                $inventory_unit_cost, $inventory_total_unit_cost,
                                $supplier_name, $supplier_contact, $inventory_id]);

        $inventory_id = $pdo->lastInsertId();

        $_SESSION['success_message'] = "Item successfully updated in the inventory!";

        
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding inventory: " . $err->getMessage();
        header("Location: ../inventory.php");
        exit;
    }

    header("Location: ../inventory.php");
    exit;
} 
