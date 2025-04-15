<?php

$required_role = "OWNER";
require __DIR__ . '/../actions/auth.php';

include __DIR__ . "/../includes/header.php";

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt = $pdo->query("SELECT * FROM tblcategory");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <?= htmlspecialchars($error_message) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4 text-custom">Categories List</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>

        <div class="d-flex justify-content-center">
            <div class="table-responsive w-75">
                <table id="categoriesTable" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($category['category_name']) ?></td>
                                <td class="text-center">
                                    <div class="">
                                        <button type="button" class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                            data-bs-category-id="<?= $category['category_id'] ?>"
                                            data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                            <img src="../assets/image/edit.svg" alt="Edit" class="" style="max-width: 2em;">
                                        </button>
                                        <button type="button" class="btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                            data-bs-category-id="<?= $category['category_id'] ?>"
                                            data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                            <img src="../assets/image/delete.svg" alt="Delete" class="img-responsive" style="max-width: 2em;">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/categories/add-category.php">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="category_name" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mx-3">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/categories/edit-category.php">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="category_name" id="editCategoryName" required>
                        <input type="hidden" class="form-control" name="category_id" id="editCategoryId">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary mx-3">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
                <form method="POST" action="/AmitieCafe/admin/categories/delete-category.php">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="category_id" id="deleteCategoryId">
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger mx-3">Delete</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
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

        document.body.addEventListener("click", function(event) {
            if (event.target.closest(".delete-btn")) {
                let button = event.target.closest(".delete-btn");
                let categoryId = button.getAttribute("data-bs-category-id");
                let categoryName = button.getAttribute("data-bs-category-name");

                document.getElementById("deleteCategoryId").value = categoryId;
                document.getElementById("deleteCategoryName").textContent = categoryName;
            }

            if (event.target.closest(".edit-btn")) {
                let button = event.target.closest(".edit-btn");
                let categoryId = button.getAttribute("data-bs-category-id");
                let categoryName = button.getAttribute("data-bs-category-name");

                document.getElementById("editCategoryId").value = categoryId;
                document.getElementById("editCategoryName").value = categoryName;
            }
        });
    });
</script>