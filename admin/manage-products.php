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
                p.category_id,
                c.category_id,
                c.category_name,
                p.subcategory_id,
                sc.subcategory_id,
                sc.subcategory_name,
                p.product_desc,
                p.created_at,
                p.updated_at,
                ps.product_size_id,
                ps.product_size,
                ps.product_size_price,
                ps.product_size_cost
            FROM tblproduct p
            LEFT JOIN tblcategory c ON p.category_id = c.category_id
            LEFT JOIN tblsubcategory sc ON p.subcategory_id = sc.subcategory_id
            LEFT JOIN tblproductsize ps ON p.product_id = ps.product_id"
    );

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stmt = $pdo->query("SELECT * FROM tblcategory");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT * FROM tblsubcategory");
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SHOW COLUMNS FROM tblproductsize WHERE Field = 'product_size'");
    $product_sizes = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product_sizes && isset($product_sizes['Type'])) {
        // Extract ENUM values
        preg_match("/^enum\((.*)\)$/", $product_sizes['Type'], $matches);
        // Parse ENUM values
        $enumValues = str_getcsv($matches[1], ",", "'");
    }
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
        <h2 class="mb-4 text-custom">Products List</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <div class="table-responsive">
            <table id="productsTable" class="table">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Subcategory</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">Size</th>
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
                            <td class="text-center"><?= htmlspecialchars($product['subcategory_name']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($product['product_desc']) ?></td>
                            <td class="text-center">₱<?= number_format($product['product_size_price'], 2) ?></td>
                            <td class="text-center">₱<?= number_format($product['product_size_cost'], 2) ?></td>
                            <td class="text-center"><?= htmlspecialchars($product['product_size']) ?></td>
                            <td class="text-center"><?= date("Y-m-d", strtotime($product['created_at'])) ?></td>
                            <td class="text-center"><?= date("Y-m-d", strtotime($product['updated_at'])) ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-around">
                                    <button class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                        data-bs-product-id="<?= $product['product_id'] ?>"
                                        data-bs-product-name="<?= $product['product_name'] ?>"
                                        data-bs-category-id="<?= $product['category_id'] ?>"
                                        data-bs-subcategory-id="<?= $product['subcategory_id'] ?>"
                                        data-bs-product-desc="<?= $product['product_desc'] ?>"
                                        data-bs-product-price="<?= $product['product_size_price'] ?>"
                                        data-bs-product-cost="<?= $product['product_size_cost'] ?>"
                                        data-bs-product-size-id="<?= $product['product_size'] ?>">
                                        <img src="../assets/image/edit.svg" alt="Edit" class="img-responsive" style="max-width: 2em;">
                                    </button>
                                    <button type="button" class="btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                        data-bs-product-id="<?= $product['product_id'] ?>"
                                        data-bs-product-name="<?= htmlspecialchars($product['product_name']) ?>">
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

        // Convert the PHP array into JSON format which JS understands
        const allSubcategories = <?php echo json_encode($subcategories); ?>

        // Reference for the select id's
        const categorySelect = document.getElementById("category_id");
        const subcategorySelect = document.getElementById("subcategory_id");
        const editCategorySelect = document.getElementById("editCategoryId");
        const editSubcategorySelect = document.getElementById("editSubcategoryId");

        // If user changes category it calls loadSubcategories function to update the corresponding subcategories
        categorySelect.addEventListener("change", function(){
            loadSubcategories(categorySelect, subcategorySelect);
        });

        // If user changes category it calls loadSubcategories function to update the corresponding subcategories
        editCategorySelect.addEventListener("change", function(){
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

            if(categoryId) {
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