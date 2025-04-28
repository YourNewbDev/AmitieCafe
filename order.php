<?php

$required_role = "OWNER";
require __DIR__ . './actions/auth.php';

include __DIR__ . "./includes/header.php";

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

    $status = "WHERE pr.productorder_status = 'ORDERED'";

    if ($filter == "RECEIVED") {
        $status = "WHERE pr.productorder_status = 'RECEIVED'";
    }
    elseif ($filter == "CANCELLED") {
        $status = "WHERE pr.productorder_status = 'CANCELLED'";
    }

    $stmt = $pdo->prepare("SELECT ord.*, pr.*, p.* FROM tblorder AS ord
    INNER JOIN tblproductorder AS pr ON pr.productorder_id = ord.order_id
    INNER JOIN tblpayment AS p ON p.payment_id = ord.order_id
    $status AND DATE(ord.created_at) = CURRENT_DATE ORDER BY created_at ASC");

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
                    <?= htmlspecialchars($error_message) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4 text-custom">Orders List</h2>

        <div class="d-flex justify-content-between mb-3">
            <a class="btn btn-primary" href="pos.php">Add Order</a>
            <form method="get" class="d-flex">
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter by</option>
                    <?php foreach ($enumValues as $values) : ?>
                    <option value="<?= $values?>"><?= htmlspecialchars($values)?></option>
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
                    <?php foreach ($ordered_list as $order) : ?>
                    <tr>
                        <td class="text-center"><?= date("Y-m-d", strtotime($order['created_at'])) ?></td>
                        <td class="text-center"><?= htmlspecialchars($order['order_id']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($order['productorder_status']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($order['user_id']) ?></td>
                        <td class="text-center">
                            <div class="">
                                <button type="button" class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                    data-bs-category-id="<?= $category['category_id'] ?>"
                                    data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                    <img src="./assets/image/edit.svg" alt="Edit" class="" style="max-width: 2em;">
                                </button>
                                <button type="button" class="btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal"
                                    data-bs-category-id="<?= $category['category_id'] ?>"
                                    data-bs-category-name="<?= htmlspecialchars($category['category_name']) ?>">
                                    <img src="./assets/image/delete.svg" alt="Delete" class="img-responsive" style="max-width: 2em;">
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
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <div class="d-flex">
                                    <select class="form-control" name="category_id" id="category_id" required>
                                        <option value="">Select a category</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category['category_id'] ?>" <?= ($selected_category == $category['category_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subcategory</label>
                                <div class="d-flex">
                                    <select class="form-control" name="subcategory_id" id="subcategory_id" required disabled>
                                        <option value="">Select a subcategory</option>
                                        <?php foreach ($subcategories as $subcategory) : ?>
                                            <option value="<?= $subcategory['subcategory_id'] ?>" <?= ($selected_subcategory == $subcategory['subcategory_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($subcategory['subcategory_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea type="text" class="form-control" name="product_desc"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="product_size_price" id="product_size_price" required onkeydown="return blockInvalidInput(event)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cost</label>
                                <input type="number" step="0.01" class="form-control" name="product_size_cost" id="product_size_cost" required onkeydown="return blockInvalidInput(event)">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Size</label>
                                <span class="badge text-bg-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Choose regular size for meals as default.">i</span>
                                <select class="form-control" name="product_size" required>
                                    <option value="">Select a size</option>
                                    <?php foreach ($enumValues as $value) : ?>
                                        <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
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

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/admin/products/edit-product.php">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="product_name" id="editProductName" required>
                        <input type="hidden" class="form-control" name="product_id" id="editProductId">
                    </div>
                    <!-- Category Selection with Edit Button -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <div class="d-flex">
                                    <select class="form-control" name="category_id" id="editCategoryId" required>
                                        <option value="">Select a category</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category['category_id'] ?>">
                                                <?= htmlspecialchars($category['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subcategory</label>
                                <div class="d-flex">
                                    <select class="form-control" name="subcategory_id" id="editSubcategoryId" required disabled>
                                        <option value="">Select a subcategory</option>
                                        <?php foreach ($subcategories as $subcategory) : ?>
                                            <option value="<?= $subcategory['subcategory_id'] ?>">
                                                <?= htmlspecialchars($subcategory['subcategory_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea type="text" class="form-control" name="product_desc" id="editProductDesc"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="product_price" id="editProductPrice" required onkeydown="return blockInvalidInput(event)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cost</label>
                                <input type="number" step="0.01" class="form-control" name="product_cost" id="editProductCost" required onkeydown="return blockInvalidInput(event)">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Size</label>
                                <span class="badge text-bg-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-bs-title="Choose regular size for meals as default.">i</span>
                                <select class="form-control" name="product_size" id="editProductSizeId" required>
                                    <option value="">Select a size</option>
                                    <?php foreach ($enumValues as $value) : ?>
                                        <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($value) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
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
<div class="modal fade" id="deleteProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                <form method="POST" action="/AmitieCafe/admin/products/delete-product.php">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="product_id" id="deleteProductId">
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

<?php include __DIR__ . "./includes/footer.php"; ?>
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

        // Convert the PHP array into JSON format which JS understands
        const allSubcategories = <?php echo json_encode($subcategories); ?>

        // Reference for the select id's
        const categorySelect = document.getElementById("category_id");
        const subcategorySelect = document.getElementById("subcategory_id");
        const editCategorySelect = document.getElementById("editCategoryId");
        const editSubcategorySelect = document.getElementById("editSubcategoryId");

        // If user changes category it calls loadSubcategories function to update the corresponding subcategories
        categorySelect.addEventListener("change", function() {
            loadSubcategories(categorySelect, subcategorySelect);
        });

        // If user changes category it calls loadSubcategories function to update the corresponding subcategories
        editCategorySelect.addEventListener("change", function() {
            loadSubcategories(editCategorySelect, editSubcategorySelect);
        });

        // Function to load subcategories based on selected category
        function loadSubcategories(categorySelect, subcategorySelect) {
            // Stores the selected category 
            const selectedCategoryId = categorySelect.value;

            // Clears the options back to default value in subcategory when a category selected changes
            subcategorySelect.innerHTML = '<option value="">Select a subcategory</option>';
            subcategorySelect.disabled = true;

            // Checks if a selected category is not empty and executes insides the statement and filters the matching subcategoryies
            if (selectedCategoryId) {
                // Filters the subcategory array based on selected category_id
                const filteredSubcategories = allSubcategories.filter(sub => sub.category_id === selectedCategoryId);

                // Begins a loop over the filtered subcategories array and iterates through each subcategory in the array
                // and sub refers to the current subcategory being processed in the loop
                filteredSubcategories.forEach(sub => {
                    // Will create the subcategory based on the selected category
                    const option = document.createElement("option");
                    option.value = sub.subcategory_id;
                    option.textContent = sub.subcategory_name;
                    subcategorySelect.appendChild(option);
                });

                // Checks if there are any subcategories that match the selected category
                if (filteredSubcategories.length > 0) {
                    subcategorySelect.disabled = false;
                }
            }
        }

        // Triggers edit modal to auto load subcategories in selected category
        const openEditModal = () => {
            const categoryId = document.getElementById("editCategoryId").value;

            if (categoryId) {
                loadSubcategories(editCategorySelect, editSubcategorySelect);
            }
        }

        // calls openEditModal function when its opened
        const editModal = document.getElementById("editProductModal");
        if (editModal) {
            editModal.addEventListener('show.bs.modal', openEditModal);
        }

        document.body.addEventListener("click", function(event) {
            if (event.target.closest(".delete-btn")) {
                let button = event.target.closest(".delete-btn");
                let productId = button.getAttribute("data-bs-product-id");
                let productName = button.getAttribute("data-bs-product-name");

                document.getElementById("deleteProductId").value = productId;
                document.getElementById("deleteProductName").textContent = productName;
            }

            if (event.target.closest(".edit-btn")) {
                let button = event.target.closest(".edit-btn");
                let productId = button.getAttribute("data-bs-product-id");
                let productName = button.getAttribute("data-bs-product-name");
                let categoryId = button.getAttribute("data-bs-category-id");
                let subcategoryId = button.getAttribute("data-bs-subcategory-id");
                let productDesc = button.getAttribute("data-bs-product-desc");
                let productPrice = button.getAttribute("data-bs-product-price");
                let productCost = button.getAttribute("data-bs-product-cost");
                let productSizeId = button.getAttribute("data-bs-product-size-id");

                document.getElementById("editProductId").value = productId;
                document.getElementById("editProductName").value = productName;
                document.getElementById("editCategoryId").value = categoryId;
                document.getElementById("editSubcategoryId").value = subcategoryId;
                document.getElementById("editProductDesc").value = productDesc;
                document.getElementById("editProductPrice").value = productPrice;
                document.getElementById("editProductCost").value = productCost;
                document.getElementById("editProductSizeId").value = productSizeId;

            }
        });

    });
</script>