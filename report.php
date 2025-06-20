<?php

include __DIR__ . "/./includes/header.php";

require __DIR__ . '/./actions/auth.php';

$user_id = $_SESSION['user_id'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {


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
        <h2 class="mb-4 text-custom">Reporting</h2>

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