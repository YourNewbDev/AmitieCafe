<?php

require __DIR__ . '/./actions/auth.php';

include __DIR__ . "/./includes/header.php";

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

try {
    $stmt = $pdo->prepare('SELECT * FROM tblinventory');
    $stmt->execute();
    $inventories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2 class="mb-4 text-custom">Inventory List</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addInventoryModal">Add Item</button>

        <div class="d-flex justify-content-center">
            <div class="table-responsive w-100">
                <table id="categoriesTable" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Item</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Unit Cost</th>
                            <th class="text-center">Total Cost</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventories as $item) : ?>
                            <?php
                                $amount_parts = explode(" ", $item["inventory_amount"]);
                                $numeric_amount = $amount_parts[0] ?? null;
                                $unit = $amount_parts[1] ?? null;
                            ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($item['inventory_name']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($item['inventory_amount']) ?></td>
                                <td class="text-center"><?= number_format($item['inventory_unit_cost'], 2) ?></td>
                                <td class="text-center"><?= number_format($item['inventory_total_unit_cost'], 2) ?></td>
                                <td class="text-center">
                                    <?= htmlspecialchars($item['supplier_name']) ?> <br>
                                    <?= htmlspecialchars($item['supplier_contact']) ?>
                                </td>
                                <td class="text-center">
                                    <div class="">
                                        <button type="button" class="btn edit-btn" data-bs-toggle="modal" data-bs-target="#editInventoryModal"
                                            data-bs-inventory-id="<?= $item['inventory_id'] ?>"
                                            data-bs-inventory-name="<?= htmlspecialchars($item['inventory_name']) ?>"
                                            data-bs-inventory-amount="<?= htmlspecialchars($numeric_amount) ?>"
                                            data-bs-inventory-measure="<?= htmlspecialchars($unit) ?>"
                                            data-bs-inventory-unit-cost="<?= number_format($item['inventory_unit_cost'], 2) ?>"
                                            data-bs-inventory-total-unit-cost="<?= number_format($item['inventory_total_unit_cost'], 2)?>"
                                            data-bs-inventory-supplier-name="<?=htmlspecialchars($item['supplier_name']) ?>"
                                            data-bs-inventory-supplier-contact="<?=htmlspecialchars($item['supplier_contact']) ?>">
                                            <img src="./assets/image/edit.svg" alt="Edit" class="" style="max-width: 2em;">
                                        </button>
                                        <button type="button" class="btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteInventoryModal"
                                            data-bs-inventory-id="<?= $item['inventory_id'] ?>"
                                            data-bs-inventory-name="<?= htmlspecialchars($item['inventory_name']) ?>">
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

    </div>
</main>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Add Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/actions/add-inventory.php">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" class="form-control" name="inventory_name" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="inventory_amount" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <label for=""></label>
                            <select class="form-select" aria-label="" name="inventory_measure" required>
                                <option selected></option>
                                <option value="kg">kg</option>
                                <option value="pc">pc</option>
                                <option value="liter">liter</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Cost</label>
                        <input type="number" class="form-control" name="inventory_total_unit_cost" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" name="supplier_contact" required>
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
<div class="modal fade" id="editInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="/AmitieCafe/actions/edit-inventory.php">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" class="form-control" name="inventory_name" id="editInventoryName" required>
                        <input type="hidden" class="form-control" name="inventory_id" id="editInventoryId">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="inventory_amount" id="editInventoryAmount" required>
                        </div>
                        <div class="col-md-4 pt-2">
                            <label for=""></label>
                            <select class="form-select" aria-label="" name="inventory_measure" id="editInventoryMeasureAmount" required>
                                <option selected></option>
                                <option value="kg">kg</option>
                                <option value="pc">pc</option>
                                <option value="liter">liter</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Cost</label>
                        <input type="number" class="form-control" name="inventory_total_unit_cost" id="editInventoryTotalUnitCost" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" name="supplier_name" id="editSupplierName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text" class="form-control" name="supplier_contact" id="editSupplierContact" required>
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
<div class="modal fade" id="deleteInventoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Are you sure you want to delete <strong id="deleteInventoryName"></strong>?</p>
                <form method="POST" action="/AmitieCafe/actions/delete-inventory.php">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="inventory_id" id="deleteInventoryId">
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

            document.body.addEventListener("click", function(event) {
            if (event.target.closest(".delete-btn")) {
                let button = event.target.closest(".delete-btn");
                let inventoryId = button.getAttribute("data-bs-inventory-id");
                let inventoryName = button.getAttribute("data-bs-inventory-name");

                document.getElementById("deleteInventoryId").value = inventoryId;
                document.getElementById("deleteInventoryName").textContent = inventoryName;
            }

            if (event.target.closest(".edit-btn")) {
                let button = event.target.closest(".edit-btn");
                let inventoryId = button.getAttribute("data-bs-inventory-id");
                let inventoryName = button.getAttribute("data-bs-inventory-name");
                let inventoryAmount = button.getAttribute("data-bs-inventory-amount");
                let inventoryMeasure = button.getAttribute("data-bs-inventory-measure");
                let inventoryTotalUnitCost = button.getAttribute("data-bs-inventory-total-unit-cost");
                let supplierName = button.getAttribute("data-bs-inventory-supplier-name");
                let supplierContact = button.getAttribute("data-bs-inventory-supplier-contact");
    
                document.getElementById("editInventoryId").value = inventoryId;
                document.getElementById("editInventoryName").value = inventoryName;
                document.getElementById("editInventoryAmount").value = inventoryAmount;
                document.getElementById("editInventoryMeasureAmount").value = inventoryMeasure;
                document.getElementById("editInventoryTotalUnitCost").value = inventoryTotalUnitCost;
                document.getElementById("editSupplierName").value = supplierName;
                document.getElementById("editSupplierContact").value = supplierContact;
            }
        });
</script>