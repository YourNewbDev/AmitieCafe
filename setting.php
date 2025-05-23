<?php

session_start();

require_once 'config/database.php';

require __DIR__ . '/./actions/auth.php';

$user_id = $_SESSION['user_id'];
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
$errors = [];

try {
    $stmt_fetch_user = $pdo->prepare('SELECT * FROM tbluser
                                    WHERE user_id = ?');
    $stmt_fetch_user->execute([$user_id]);
    $user = $stmt_fetch_user->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $user_firstName = $_POST["user_firstName"] ?? null;
        $user_lastName = $_POST["user_lastName"] ?? null;
        $hashed_password = $user['user_password'];
        $current_password = $_POST['current_password'] ?? null;
        $new_password = $_POST['new_password'] ?? null;
        $confirm_new_password = $_POST['confirm_new_password'] ?? null;
        $user_email = $_POST["user_email"] ?? null;
        $user_confirm_email = $_POST["user_confirm_email"] ?? null;
        $phone = $_POST["phone"] ?? null;

        if (empty($user_firstName) || ctype_space($user_firstName)) {
            $errors[] = "Please enter first name";
        }

        if (empty($user_lastName) || ctype_space($user_lastName)) {
            $errors[] = "Please enter last name";
        }

        if (empty($user_email) || ctype_space($user_email) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is empty or invalid";
        }

        if (empty($user_confirm_email) || $user_email !== $user_confirm_email) {
            $errors[] = "Emails do not match";
        }
        
        if (empty($phone)) {
            $errors[] = "Phone number is required";
        } elseif (!preg_match('/^\d{7,11}$/', $phone)) {
            $errors[] = "Phone number must be 7 to 11 digits with no spaces or symbols";
        }

        if (!empty($new_password) || !empty($confirm_new_password)) {
            if (empty($current_password) || ctype_space($current_password)) {
                $errors[] = "Please enter your current password to change your password";
            } elseif (!password_verify($current_password, $hashed_password)) {
                $errors[] = "Incorrect current password";
            }
        
            if ($new_password !== $confirm_new_password) {
                $errors[] = "New passwords do not match";
            }
        }

        if (!empty($errors)) {
            $error_message = implode("<br>", $errors);
        } else {
            $updated_password = $user['user_password'];

            if(!empty($new_password)) {
                $updated_password = password_hash($new_password, PASSWORD_DEFAULT);
            }

            $stmt_update_user = $pdo->prepare('UPDATE tbluser
                                            SET user_firstName = ?,
                                            user_lastName = ?,
                                            user_email = ?,
                                            user_phone = ?,
                                            user_password = ?,
                                            updated_at = NOW()
                                            WHERE user_id = ?');
            $stmt_update_user->execute([
                $user_firstName,
                $user_lastName,
                $user_email,
                $phone,
                $updated_password,
                $user_id
            ]);

            $_SESSION['success_message'] = 'Profile updated successfully.';
            header("Location: /AmitieCafe/setting.php");
            exit;
        }
    }

} catch (PDOException $err) {
    die("Query failed: " . $err->getMessage());
}

include __DIR__ . "/./includes/header.php";

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
        <div class="container bg-custom mb-2 p-4 border border-dark rounded-top text-white">
            <h3 class=""><?= htmlspecialchars($user['user_name']) ?></h3>
            <h6 class=""><?= htmlspecialchars($user['user_role']) ?></h6>
            <span class="fs-6 fw-lighter fst-italic5">
                Created: <?= date("Y-m-d", strtotime($user['created_at'])) ?>
            </span>
        </div>

        <form method="POST" class="bg-custom p-5 mb-3 border border-dark rounded-top">
            <div class="d-flex justify-content-start mb-2">
                <h3 class="text-white">Edit Profile</h3>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">First Name</label>
                    <input class="form-control" name="user_firstName" type="text" value="<?= htmlspecialchars($user['user_firstName'])?>" placeholder="First Name" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Last Name</label>
                    <input class="form-control" name="user_lastName" type="text" value="<?= htmlspecialchars($user['user_lastName'])?>" placeholder="Last Name" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Phone No.</label>
                    <input class="form-control" name="phone" type="number" value="<?= htmlspecialchars($user['user_phone']) ?>" placeholder="Phone No." required>
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
            <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Current Password</label>
                    <input class="form-control" name="current_password" type="password" placeholder="********">
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold"> New Password</label>
                    <input class="form-control" name="new_password" type="password" placeholder="********">
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Confirm New Password</label>
                    <input class="form-control" name="confirm_new_password" type="password" placeholder="********">
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-5">
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Email Address</label>
                    <input class="form-control" name="user_email" type="email" value="<?= htmlspecialchars($user['user_email']) ?>" placeholder="email@example.com" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Confirm Email Address</label>
                    <input class="form-control" name="user_confirm_email" type="email" value="<?= htmlspecialchars($user['user_email']) ?>" placeholder="email@example.com" required>
                </div>
                <div class="d-flex flex-column me-3">
                    <label class="form-label text-white fw-bold">Last Updated</label>
                    <input class="form-control" type="text" value="<?= date("Y-m-d", strtotime($user['updated_at'])) ?>" placeholder="email@example.com" disabled>
                </div>
            </div>
            <div class="d-flex justify-content-center mb-4">
                <div class="d-flex flex-column me-3">
                    <button type="submit" class="btn btn-light">Save changes</button>
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