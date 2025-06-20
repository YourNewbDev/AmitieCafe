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

    $stmt_total_order = $pdo->prepare("SELECT COUNT(*) AS total_order FROM tblorder");
    $stmt_total_order->execute();
    $total_order = $stmt_total_order->fetch();

    $stmt_sale_per_day = $pdo->prepare("SELECT ROUND(SUM(daily_total) / COUNT(DISTINCT order_date), 2) AS avg_sales_per_day
    FROM (
        SELECT DATE(o.created_at) AS order_date,
               SUM(po.productorder_total) AS daily_total
        FROM tblorder o
        JOIN tblproductorder po ON o.order_id = po.order_id
        GROUP BY DATE(o.created_at)
    ) AS daily_sales
");
    $stmt_sale_per_day->execute();
    $sale_per_day = $stmt_sale_per_day->fetch();


    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

    $status = "WHERE pr.productorder_status = 'ORDERED'";

    if ($filter == "RECEIVED") {
        $status = "WHERE pr.productorder_status = 'RECEIVED'";
    } elseif ($filter == "CANCELLED") {
        $status = "WHERE pr.productorder_status = 'CANCELLED'";
    }

    $stmt = $pdo->prepare("SELECT ord.*, pr.*, p.* FROM tblorder AS ord
    INNER JOIN tblproductorder AS pr ON pr.order_id = ord.order_id
    INNER JOIN tblpayment AS p ON p.payment_id = ord.payment_id
    $status AND DATE(ord.created_at) = CURRENT_DATE ORDER BY created_at ASC");

    // $stmt = $pdo->prepare("SELECT ord.*, pr.*, p.*
    // FROM tblorder AS ord
    // INNER JOIN tblproductorder AS pr ON pr.order_id = ord.order_id
    // INNER JOIN tblpayment AS p ON p.payment_id = ord.payment_id
    // $status
    // AND DATE(ord.created_at) = CURRENT_DATE
    // ORDER BY ord.created_at ASC");

    $stmt->execute();
    $ordered_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SHOW COLUMNS FROM tblproductorder WHERE Field = 'productorder_status'");
    $productorder_status = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($productorder_status && isset($productorder_status['Type'])) {
        // Extract ENUM values
        preg_match("/^enum\((.*)\)$/", $productorder_status['Type'], $matches);
        // Parse ENUM values
        $enumValues = str_getcsv($matches[1], ",", "'");
    }

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
                    <?= $error_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3">
       <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3 align-items-stretch mb-4">
            <!-- Average Orders per Day Card -->
            <div class="col">
                <div class="card bg-custom h-100">
                    <div class="card-body text-white text-center">
                        <h5 class="card-header">Avg Orders / Day</h5>
                        <h4><?php echo number_format($order['ave_orders_per_day'] ?? 0, 2) ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Average Sales per Day Card -->
            <div class="col">
                <div class="card bg-custom h-100">
                    <div class="card-body text-white text-center">
                        <h5 class="card-header">Avg Sales / Day</h5>
                        <h4>₱<?php echo number_format($sale_per_day['avg_sales_per_day'] ?? 0, 2) ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Overall Sale Card -->
            <div class="col">
                <div class="card bg-custom h-100">
                    <div class="card-body text-white text-center">
                        <h5 class="card-header">Total Orders</h5>
                        <h4><?php echo number_format($total_order['total_order']) ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Placeholder Card (You can replace content here) -->
            <div class="col">
                <div class="card bg-custom h-100">
                    <div class="card-body text-white text-center">
                        <h5 class="card-header">Total Sales</h5>
                        <h4>₱<?php echo number_format($total_sale['total_sale'], 2) ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mb-4 text-custom">Orders Today</h2>

        <div class="d-flex justify-content-between mb-3">
            <a class="btn btn-primary" href="pos.php">New Order</a>
            <form method="get" class="d-flex">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter by</option>
                    <?php foreach ($enumValues as $values): ?>
                        <option value="<?= $values ?>"><?= htmlspecialchars($values) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>


        <div class="table-responsive">
            <table id="ordersTable" class="table">
                <thead>
                    <tr>
                        <th class="text-center">Date & Time</th>
                        <th class="text-center">Order #</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Cashier</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordered_list as $order): ?>
                        <tr>
                            <td class="text-center"><?= date("Y-m-d", strtotime($order['created_at'])) ?></td>
                            <td class="text-center"><?= htmlspecialchars($order['order_id']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($order['productorder_status']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($order['user_id']) ?></td>
                            <td class="text-center">
                                <div class="">
                                    <button type="button" class="btn edit-btn" data-bs-toggle="modal"
                                        data-bs-target="#editCategoryModal"
                                        data-bs-category-id="<?= $category['category_id'] ?>"
                                        data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                        <img src="./assets/image/edit.svg" alt="Edit" class="" style="max-width: 2em;">
                                    </button>
                                    <button type="button" class="btn delete-btn" data-bs-toggle="modal"
                                        data-bs-target="#deleteCategoryModal"
                                        data-bs-category-id="<?= $category['category_id'] ?>"
                                        data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                        <img src="./assets/image/delete.svg" alt="Delete" class="img-responsive"
                                            style="max-width: 2em;">
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


    </div>
</main>


<?php include __DIR__ . "/./includes/footer.php"; ?>
<!-- Auto Trigger Modal if a message exists -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($success_message): ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php endif; ?>

        <?php if ($error_message): ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php endif; ?>

    });
</script>