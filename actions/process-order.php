<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../actions/auth.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $stmt = $pdo->prepare("SELECT SUM(cart_total) AS total_cart FROM tblcart");
    $stmt->execute();
    $total_cart = $stmt->fetch();


    $payment_type = $_POST['payment_method'];
    $payment_amount_paid = $_POST['payment_amount_paid'];
    $payment_reference = $_POST['payment_reference'];
    $payment_bank_name = $_POST['payment_bank_name'];
    $payment_change = (float) $payment_amount_paid - (float) $total_cart['total_cart'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tblpayment (payment_type, payment_bank_name, payment_reference, payment_amount_paid, payment_change)
                            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$payment_type, $payment_bank_name, $payment_reference, $payment_amount_paid, $payment_change]);

        $payment_id = $pdo->lastInsertId();
        //code...
    } catch (\Throwable $th) {
        //throw $th;
    }
}
