<?php

$required_role = "OWNER";
require __DIR__ . '/../actions/auth.php';

include __DIR__ . "/../includes/header.php";

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt = $pdo->query("SELECT
                        sc.subcategory_id,
                        sc.subcategory_name,
                        sc.category_id,
                        c.category_id,
                        c.category_name
                        FROM tblsubcategory sc
                        LEFT JOIN tblcategory c
                        ON sc.category_id = c.category_id");

    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM tblcategory");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $err) {
    die("Query failed: " . $err->getMessage());
}

$selected_category = $_POST['category_id'] ?? null;

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
        <h2 class="mb-4 text-custom">Subcategories List</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">Add Subcategory</button>

        <div class="d-flex justify-content-center">
            <div class="table-responsive w-75">
                <table id="subcategoriesTable" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Subcategory</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subcategories as $subcategory) : ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($subcategory['subcategory_name']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($subcategory['category_name']) ?></td>
                                <td class="text-center">
                                    <div class="">
                                        <button type="button" class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal"
                                            data-bs-subcategory-id="<?= $subcategory['subcategory_id'] ?>"
                                            data-bs-subcategory-name="<?= htmlspecialchars($subcategory['subcategory_name']) ?>"
                                            data-bs-category-id="<?= htmlspecialchars($subcategory['category_id']) ?>">
                                            <img src="../assets/image/edit.svg" alt="Edit" class="img-responsive" style="max-width: 2em;">
                                        </button>
                                        <button type="button" class="btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteSubcategoryModal"
                                            data-bs-subcategory-id="<?= $subcategory['subcategory_id'] ?>"
                                            data-bs-subcategory-name="<?= htmlspecialchars($subcategory['subcategory_name']) ?>">
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

<!-- Add Subcategory Modal -->
<div class="modal fade" id="addSubcategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Add Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/subcategories/add-subcategory.php">
                    <div class="mb-3">
                        <label class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" name="subcategory_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <select class="form-control" name="category_id" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['category_id'] ?>" <?= ($selected_category == $category['category_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
<div class="modal fade" id="editSubcategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/subcategories/edit-subcategory.php">
                    <div class="mb-3">
                        <label class="form-label">Subcategory Name</label>
                        <input type="text" class="form-control" name="subcategory_name" id="editSubcategoryName" required>
                        <input type="hidden" class="form-control" name="subcategory_id" id="editSubcategoryId">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <select class="form-control" name="category_id" id="editCategoryId" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
<div class="modal fade" id="deleteSubcategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Are you sure you want to delete <strong id="deleteSubcategoryName"></strong>?</p>
                <form method="POST" action="/AmitieCafe/admin/subcategories/delete-subcategory.php">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="subcategory_id" id="deleteSubcategoryId">
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
                let subcategoryId = button.getAttribute("data-bs-subcategory-id");
                let subcategoryName = button.getAttribute("data-bs-subcategory-name");

                document.getElementById("deleteSubcategoryId").value = subcategoryId;
                document.getElementById("deleteSubcategoryName").textContent = subcategoryName;
            }

            if (event.target.closest(".edit-btn")) {
                let button = event.target.closest(".edit-btn");
                let subcategoryId = button.getAttribute("data-bs-subcategory-id");
                let subcategoryName = button.getAttribute("data-bs-subcategory-name");
                let categoryId = button.getAttribute("data-bs-category-id");

                document.getElementById("editSubcategoryId").value = subcategoryId;
                document.getElementById("editSubcategoryName").value = subcategoryName;
                document.getElementById("editCategoryId").value = categoryId;

                // This will loop through the category dropdown to ensure the correct category is selected
                // let categoryDropdown = document.getElementById("editCategoryId");

                // for (let option of categoryDropdown.options) {
                //     option.selected = option.value === categoryId;
                // }
            }
        });
    });
</script>