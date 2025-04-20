<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../actions/auth.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $product_size_id = trim($_POST['product_size_id']);
    $quantity = (int) trim($_POST['quantity']);
    $price = (float) trim($_POST['product_size_price']);
    $total = $price * $quantity;

    try {

        if (isset($_POST['add'])) {
            $stmt = $pdo->prepare("UPDATE tblcart SET cart_qty = cart_qty + ?,
                                        cart_total = cart_total + ?
                                        WHERE product_size_id = ?");
            $stmt->execute([$quantity, $total, $product_size_id]);
        } elseif (isset($_POST['sub'])) {
            $stmt = $pdo->prepare("UPDATE tblcart SET cart_qty = cart_qty - ?,
                                        cart_total = cart_total - ?
                                        WHERE product_size_id = ?");
            $stmt->execute([$quantity, $total, $product_size_id]);

            $stmt = $pdo->prepare("SELECT * FROM tblcart WHERE product_size_id = ?");
            $stmt->execute([$product_size_id]);
            $checkQuantity = $stmt->fetch();

            if ($checkQuantity && $checkQuantity['cart_qty'] <= 0) {
                $stmt = $pdo->prepare("DELETE FROM tblcart WHERE product_size_id = ?");
                $stmt->execute([$product_size_id]);
            } elseif ($product_size_id <= 0) {
                $$stmt = $pdo->prepare("DELETE FROM tblcart WHERE product_size_id = ?");
                $stmt->execute([$product_size_id]);
            }
        }
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "Error adding product: " . $err->getMessage();
    }

    header("Location: /AmitieCafe/pos.php");
    exit;
}
