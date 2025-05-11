<?php

include __DIR__ . "/./includes/header.php";

require __DIR__ . '/./actions/auth.php';

$user_id = $_SESSION['user_id'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt_order = $pdo->prepare("SELECT ROUND(SUM(order_count) / COUNT(DISTINCT date), 2) AS ave_orders_per_day
                                FROM (
                                    SELECT CURDATE() - INTERVAL seq DAY AS date, COUNT(o.order_id) AS order_count
                                    FROM (
                                        SELECT 0 AS seq UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                                        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                                        UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
                                        UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
                                        UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
                                        UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
                                    ) AS seqs
                                    LEFT JOIN tblorder o ON DATE(o.created_at) = CURDATE() - INTERVAL seq DAY
                                    GROUP BY date
                                ) AS daily_orders
                                WHERE order_count > 0");
    $stmt_order->execute();
    $order = $stmt_order->fetch();

    $stmt_total_sale = $pdo->prepare("SELECT SUM(productorder_total) AS total_sale FROM tblproductorder");
    $stmt_total_sale->execute();
    $total_sale = $stmt_total_sale->fetch();

} catch (PDOException $err) {
    die("Query failed: " . $err->getMessage());
}

?>

<!-- Main Content (Scrollable) -->
<main class="main-content d-flex flex-column flex-grow-1 overflow-auto pt-3 px-3">
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?=$error_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
        <div class="container mb-2 p-4">
           <div class="row">
                <div class="col-md-4">
                    <div class="card bg-success" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-header text-white text-center">Average Order/Day</h5>
                            <h1 class="card-title text-white text-center"><?php echo number_format($order['ave_orders_per_day'], 2)?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-header text-white text-center">Overall Sale</h5>
                            <h1 class="card-title text-white text-center">₱<?php echo number_format($total_sale['total_sale'],2) ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title text-white">Card title</h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
           </div>
        </div>

        
    </div>
</main>


<?php include __DIR__ . "/./includes/footer.php"; ?>
<!-- Auto Trigger Modal if a message exists -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($success_message) : ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php endif; ?>

        <?php if ($error_message) : ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>

    });
</script>