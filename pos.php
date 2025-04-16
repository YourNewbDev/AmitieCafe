<?php include "includes/header.php"; ?>

<?php
// Fetch all categories
$stmt = $pdo->prepare("SELECT * FROM tblcategory");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all products grouped by category and subcategory for default display
$allData = [];
foreach ($categories as $category) {
    $stmt = $pdo->prepare("SELECT * FROM tblsubcategory WHERE category_id = ?");
    $stmt->execute([$category['category_id']]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $subcatData = [];
    foreach ($subcategories as $subcategory) {
        $stmt = $pdo->prepare("SELECT * FROM tblproduct
                                WHERE subcategory_id = ?");
        $stmt->execute([$subcategory['subcategory_id']]);
        $subcategory['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $subcatData[] = $subcategory;
    }

    $category['subcategories'] = $subcatData;
    $allData[] = $category;
}
?>

<div class="main-content">
    <div class="row g-0">
        <main class="col-md-7 d-flex flex-column flex-grow-1 overflow-auto vh-100">
            <!-- Category Buttons -->
            <h4 class="mt-4 text-primary text-center">Categories</h4>
            <div class="scrollable-menu pt-2 me-5">
                <form action="/AmitieCafe/pos.php" method="GET">
                    <button class="btn custom-btn btn-lg rounded-top m-2 w-50 fs-3 h-100" name="category_id" value="0" type="submit">ALL</button>
                    <?php foreach ($categories as $category) : ?>
                        <button class="btn custom-btn btn-lg rounded-top m-2 w-50 fs-3 h-100" name="category_id" value="<?= (int)$category['category_id'] ?>" type="submit">
                            <?= htmlspecialchars($category['category_name']); ?>
                        </button>
                    <?php endforeach; ?>
                </form>
            </div>

            <?php if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['category_id']) && $_GET['category_id'] != 0): ?>
                <?php
                $category_id = (int) $_GET['category_id'];
                $stmt = $pdo->prepare("SELECT * FROM tblsubcategory WHERE category_id = ?");
                $stmt->execute([$category_id]);
                $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <!-- Display all products under the selected category, grouped by subcategory -->
                <div class="row">
                    <div class="col">
                        <?php foreach ($subcategories as $subcategory): ?>
                            <h5 class="mt-3 text-secondary"><?= htmlspecialchars($subcategory['subcategory_name']); ?></h5>

                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM tblproduct WHERE subcategory_id = ?");
                            $stmt->execute([$subcategory['subcategory_id']]);
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>

                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <button class="btn custom-btn-prod m-2 w-25" data-bs-toggle="modal" data-bs-target="#productModal<?= $product['product_id']; ?>">
                                        <?= htmlspecialchars($product['product_name']); ?>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="productModal<?= $product['product_id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $product['product_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <?php
                                                // Fetch sizes, price, and cost for the product
                                                $stmt = $pdo->prepare("SELECT * FROM tblproductsize WHERE product_id = ?");
                                                $stmt->execute([$product['product_id']]);
                                                $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel<?= $product['product_id']; ?>">
                                                        <?= htmlspecialchars($product['product_name']); ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p><strong>Description:</strong> <?= htmlspecialchars($product['product_desc']); ?></p>
                                                    <div class="list-group">
                                                        <!-- Loop through each size and display its price, cost, and an input field for quantity -->
                                                        <?php foreach ($sizes as $size): ?>
                                                            <div class="list-group-item list-group-item-action p-3 mb-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <h6 class="mb-1"><strong>Size:</strong> <?= htmlspecialchars($size['product_size']); ?></h6>
                                                                </div>
                                                                <p class="mb-1"><strong>Price:</strong> ₱<?= number_format($size['product_size_price'], 2); ?></p>
                                                                <p class="mb-1"><strong>Cost:</strong> ₱<?= number_format($size['product_size_cost'], 2); ?></p>

                                                                <!-- Input field for quantity and the "Add" button -->
                                                                <div class="d-flex align-items-center">
                                                                    <input type="number" id="quantity<?= $size['product_size_id']; ?>" class="form-control me-2" min="1" value="1" style="width: 70px;">
                                                                    <button class="btn btn-success" onclick="addQuantity(<?= $size['product_size_id']; ?>, <?= $product['product_id']; ?>, '<?= htmlspecialchars($product['product_name']); ?>', '<?= htmlspecialchars($size['product_size']); ?>', <?= $size['product_size_price']; ?>)">
                                                                        Add
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No products in this subcategory.</p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php else: ?>
                <!-- Default display: All categories, subcategories, and products -->
                <div class="row">
                    <div class="col">
                        <?php foreach ($allData as $category): ?>
                            <h4 class="mt-4 text-primary"><?= htmlspecialchars($category['category_name']); ?></h4>

                            <?php foreach ($category['subcategories'] as $subcategory): ?>
                                <h5 class="mt-3 text-secondary"><?= htmlspecialchars($subcategory['subcategory_name']); ?></h5>

                                <?php if (!empty($subcategory['products'])): ?>
                                    <?php foreach ($subcategory['products'] as $product): ?>
                                        <button class="btn custom-btn-prod m-2 w-25" data-bs-toggle="modal" data-bs-target="#productModal<?= $product['product_id']; ?>">
                                            <?= htmlspecialchars($product['product_name']); ?>
                                        </button>
                                        <div class="modal fade" id="productModal<?= $product['product_id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?= $product['product_id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <?php
                                                    // Fetch sizes, price, and cost for the product
                                                    $stmt = $pdo->prepare("SELECT * FROM tblproductsize WHERE product_id = ?");
                                                    $stmt->execute([$product['product_id']]);
                                                    $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel<?= $product['product_id']; ?>">
                                                            <?= htmlspecialchars($product['product_name']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p><strong>Description:</strong> <?= htmlspecialchars($product['product_desc']); ?></p>
                                                        <div class="list-group">
                                                            <!-- Loop through each size and display its price, cost, and an input field for quantity -->
                                                            <?php foreach ($sizes as $size): ?>
                                                                <div class="list-group-item list-group-item-action p-3 mb-2 bg-light rounded">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="mb-1"><strong>Size:</strong> <?= htmlspecialchars($size['product_size']); ?></h6>
                                                                    </div>
                                                                    <p class="mb-1"><strong>Price:</strong> ₱<?= number_format($size['product_size_price'], 2); ?></p>
                                                                    <p class="mb-1"><strong>Cost:</strong> ₱<?= number_format($size['product_size_cost'], 2); ?></p>

                                                                    <!-- Input field for quantity and the "Add" button -->
                                                                    <div class="d-flex align-items-center">
                                                                        <input type="number" id="quantity<?= $size['product_size_id']; ?>" class="form-control me-2" min="1" value="1" style="width: 70px;">
                                                                        <button class="btn btn-success" onclick="addQuantity(<?= $size['product_size_id']; ?>, <?= $product['product_id']; ?>, '<?= htmlspecialchars($product['product_name']); ?>', '<?= htmlspecialchars($size['product_size']); ?>', <?= $size['product_size_price']; ?>)">
                                                                            Add
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No products in this subcategory.</p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <!-- Right Sidebar (Current Sale) -->
        <aside class="col-md-5 d-flex flex-column border-start vh-100 px-3">
            <h4 class="fw-bold text-center">CURRENT SALE</h4>

            <ul id="cartItemsList" class="list-group overflow-auto flex-grow-1 mb-3">
                <!-- Cart items will be added here dynamically -->
            </ul>

            <div class="d-flex justify-content-between mb-2">
                <h3 class="fw-bold">Total:</h3>
                <h3 class="fw-bold" id="totalPrice">₱0.00</h3>
            </div>

            <button class="btn btn-success w-100 mb-3">Complete Sale</button>

            <!-- Calculator (as is) -->
            <div class="mt-auto p-3 border rounded bg-light">
                <h6 class="fw-bold text-center">Calculator</h6>
                <input type="text" id="calc-display" class="form-control text-end mb-2" disabled>
                <div class="row g-1">
                    <?php
                    $buttons = [
                        ['7', '8', '9', '/'],
                        ['4', '5', '6', '*'],
                        ['1', '2', '3', '-'],
                        ['C', '0', '=', '+'],
                    ];
                    foreach ($buttons as $row) {
                        foreach ($row as $btn) {
                            $class = is_numeric($btn) ? 'btn-secondary' : ($btn === 'C' ? 'btn-danger' : ($btn === '=' ? 'btn-success' : 'operator-btn'));
                            echo '<div class="col-3"><button class="btn ' . $class . ' w-100" onclick="handleCalc(\'' . $btn . '\')">' . $btn . '</button></div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    let cart = []; // Initialize an empty cart

    // Function to handle adding products to the cart
    function addQuantity(sizeId, productId, productName, productSize, productPrice) {
        let quantity = document.getElementById('quantity' + sizeId).value;

        if (quantity <= 0 || isNaN(quantity)) {
            alert('Please enter a valid quantity!');
            return;
        }

        // Check if the product already exists in the cart
        let existingItem = cart.find(item => item.productId === productId && item.sizeId === sizeId);
        if (existingItem) {
            existingItem.quantity += parseInt(quantity); // Update quantity if product is already in the cart
            existingItem.totalPrice = existingItem.price * existingItem.quantity;
        } else {
            // Add new item to cart
            cart.push({
                productId: productId,
                sizeId: sizeId,
                productName: productName,
                productSize: productSize,
                price: productPrice,
                quantity: parseInt(quantity),
                totalPrice: productPrice * quantity
            });
        }

        // Update the cart in the sidebar
        updateCartSidebar();
    }

    // Function to update the sidebar dynamically with cart items
    function updateCartSidebar() {
        let cartItemsList = document.getElementById('cartItemsList');
        let totalPriceElement = document.getElementById('totalPrice');

        // Clear existing items in the sidebar
        cartItemsList.innerHTML = '';

        let totalPrice = 0;

        // Loop through cart items and add them to the sidebar
        cart.forEach(item => {
            let listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
            listItem.innerHTML = `
            <div class="row w-100">
            <div class="col-6 col-sm-6 col-md-5">
                <div class="text-truncate">${item.productName} (${item.productSize})</div>
            </div>
            <div class="col-3 col-sm-2 col-md-2 d-flex justify-content-center align-items-center">
                ₱${item.price.toFixed(2)}
            </div>
            <div class="col-2 col-sm-2 col-md-2 d-flex justify-content-center align-items-center">
                ${item.quantity}
            </div>
            <div class="col-3 col-sm-2 col-md-2 d-flex justify-content-center align-items-center">
                ₱${item.totalPrice.toFixed(2)}
            </div>
        </div>
            `;
            cartItemsList.appendChild(listItem);

            totalPrice += item.totalPrice; // Update total price
        });

        // Update the total price in the sidebar
        totalPriceElement.innerText = totalPrice.toFixed(2);
    }
</script>

<?php include "includes/footer.php"; ?>