<?php

$required_role = "OWNER";
require __DIR__ . '/../actions/auth.php';

include __DIR__ . "/../includes/header.php";

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt = $pdo->prepare(
        "SELECT
                p.product_id,
                p.product_name,
                c.category_id,
                c.category_name,
                p.product_desc,
                p.product_price,
                p.product_cost,
                p.created_at,
                p.updated_at,
                ps.product_size_id,
                ps.product_size,
                ps.product_size_price
            FROM tblproduct p
            LEFT JOIN tblcategory c ON p.category_id = c.category_id
            LEFT JOIN tblproductsize ps ON p.product_id = ps.product_id"
    );

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stmt = $pdo->query("SELECT * FROM tblcategory");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM tblproductsize");
    $product_sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $err) {
    die("Query failed: " . $err->getMessage());
}

$selected_category = $_POST['category_id'] ?? null;
$selected_product_size = $_POST['product_size_id'] ?? null;

?>

<!-- Main Content (Scrollable) -->
<main class="col-md-10 d-flex flex-column flex-grow-1 overflow-auto pt-5 px-5  vh-100">
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
    <div class="container mt-4">
        <h2 class="mb-4 text-custom">Products List</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <div class="table-responsive">
            <table id="productsTable" class="table">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Size Price</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Updated</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($product['product_name']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($product['category_name']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($product['product_desc']) ?></td>
                            <td class="text-center">₱<?= number_format($product['product_price']) ?></td>
                            <td class="text-center">₱<?= number_format($product['product_cost']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($product['product_size']) ?></td>
                            <td class="text-center">₱<?= number_format($product['product_size_price']) ?></td>
                            <td class="text-center"><?= date("Y-m-d", strtotime($product['created_at'])) ?></td>
                            <td class="text-center"><?= date("Y-m-d", strtotime($product['updated_at'])) ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-around">
                                    <button class="btn btn-sm btn-warning mx-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/products/add-product.php">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="product_name" required>
                    </div>
                    <!-- Category Selection with Add Button -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <div class="d-flex">
                            <select class="form-control" name="category_id" required>
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['category_id'] ?>" <?= ($selected_category == $category['category_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea type="text" class="form-control" name="product_desc"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control" name="product_price" required onkeydown="return blockInvalidInput(event)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cost</label>
                        <input type="number" class="form-control" name="product_cost" required onkeydown="return blockInvalidInput(event)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Size</label>
                        <select class="form-control" name="product_size_id" required>
                            <option value="">Select a size</option>
                            <?php foreach ($product_sizes as $product_size) : ?>
                                <option value="<?= $product_size['product_size'] ?>" <?= ($selected_product_size == $product_size['product_size_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($product_size['product_size'] . ' - ₱' . number_format($product_size['product_size_price'], 2)) ?>
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
    });
</script>