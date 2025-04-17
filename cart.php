<?php

include "includes/header.php";

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $product_size_id = trim($_POST['product_size_id']);
    $quantity = (int) trim($_POST['quantity']);
    $price = (float) trim($_POST['product_size_price']);
    $total = $price * $quantity;

    try {
        $stmt = $pdo->prepare("INSERT INTO tblcart (product_size_id, cart_price, cart_qty, cart_total) VALUES
                                (?, ?, ?, ?)");
        $stmt->execute([$product_size_id, $price, $quantity, $total]);
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding product: " . $err->getMessage();
    }
}
