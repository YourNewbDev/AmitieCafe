<?php

require __DIR__ . '/./actions/auth.php';

include __DIR__ . "/./includes/header.php";

$user_id = $_SESSION['user_id'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt_fetch_user = $pdo->prepare('SELECT * FROM tbluser
                                    WHERE user_id = ?');
    $stmt_fetch_user->execute([$user_id]);
    $user = $stmt_fetch_user->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $err) {
    die("Query failed: " . $err->getMessage());
}


// Fetch from Table to Modal
$selected_subcategory = $_POST['subcategory_id'] ?? null;
$selected_category = $_POST['category_id'] ?? null;
$selected_product_size = $_POST['product_size_id'] ?? null;

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
                    <?= htmlspecialchars($error_message) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4 text-custom">User Setting</h2>
        <p class="fs-6">Username: <?= htmlspecialchars($user['user_name']) ?></p>
        <p class="fs-6">Role: <?= htmlspecialchars($user['user_role']) ?></p>
        <span class="fs-6 fw-lighter fst-italic5">
            Created: <?= date("Y-m-d", strtotime($user['created_at'])) ?>
        </span>

        <form action="POST">
            <div class="d-flex justify-content-evenly mb-4">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">First Name</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars($user['user_firstName'])?>" placeholder="First Name" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Last Name</label>
                    <input class="form-control" type="text" value="<?= htmlspecialchars($user['user_lastName'])?>" placeholder="Last Name" required>
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Password</label>
                    <input class="form-control" type="password" placeholder="********" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Confirm Password</label>
                    <input class="form-control" type="password" placeholder="********" required>
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Email Address</label>
                    <input class="form-control" type="email" value="<?= htmlspecialchars($user['user_email']) ?>" placeholder="email@example.com" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Confirm Email Address</label>
                    <input class="form-control" type="email" value="<?= htmlspecialchars($user['user_email']) ?>" placeholder="email@example.com" required>
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-5">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Phone No.</label>
                    <input class="form-control" type="number" value="<?= htmlspecialchars($user['user_phone']) ?>" placeholder="email@example.com" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-secondary-emphasis fw-bold">Last Updated</label>
                    <input class="form-control" type="text" value="<?= date("Y-m-d", strtotime($user['updated_at'])) ?>" placeholder="email@example.com" disabled>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-4">
                <div class="d-flex flex-column me-3">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
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