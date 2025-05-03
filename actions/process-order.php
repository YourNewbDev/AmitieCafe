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
    $payment_reference = $_POST['payment_reference'] ?? null;
    $payment_bank_name = $_POST['payment_bank_name'] ?? null;
    $payment_change = (float) $payment_amount_paid - (float) $total_cart['total_cart'];

    try {
        $stmt_payment = $pdo->prepare("INSERT INTO tblpayment (payment_type, payment_bank_name, payment_reference, payment_amount_paid, payment_change)
                            VALUES (?, ?, ?, ?, ?)");
        $stmt_payment->execute([$payment_type, $payment_bank_name, $payment_reference, $payment_amount_paid, $payment_change]);

        $payment_id = $pdo->lastInsertId();


        $stmt_order = $pdo->prepare("INSERT INTO tblorder (created_at, user_id, payment_id)
                            VALUES (NOW(), ?, ?)");

        $stmt_order->execute([$user_id, $payment_id]);

        $order_id = $pdo->lastInsertId();

        $stmt_productorder = $pdo->prepare("INSERT INTO tblproductorder (productorder_total,
                                                                        productorder_qty,
                                                                        productorder_price,
                                                                        productorder_status,
                                                                        product_size_id,
                                                                        order_id)
                                            SELECT cart_total,
                                                    cart_qty,
                                                    cart_price,
                                                    'ORDERED',
                                                    product_size_id,
                                                    :order_id
                                            FROM tblcart");

        $stmt_productorder->execute([':order_id' => $order_id]);

        $productorder_id = $pdo->lastInsertId();

        $stmt_cart = $pdo->prepare("DELETE FROM tblcart");
        $stmt_cart->execute();

        $_SESSION['success_message'] = "Order has been processed and the order# is {$order_id}";
    } catch (PDOException $err) {
        $_SESSION['error_message'] = "There was an error processing the order " . $err->getMessage();
        header("Location: ../pos.php?error=Invalid request");
        exit;

    }

    header("Location: ../order.php");
    exit;
}
