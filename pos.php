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
                                                    <?php foreach ($sizes as $size): ?>
                                                        <form action="/AmitieCafe/cart.php" method="post">
                                                            <p><strong>Description:</strong> <?= htmlspecialchars($product['product_desc']); ?></p>
                                                            <div class="list-group">
                                                                <!-- Loop through each size and display its price, cost, and an input field for quantity -->

                                                                <div class="list-group-item list-group-item-action p-3 mb-2 bg-light rounded">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="mb-1"><strong>Size:</strong> <?= htmlspecialchars($size['product_size']); ?></h6>
                                                                    </div>
                                                                    <p class="mb-1"><strong>Price:</strong> ₱<?= number_format($size['product_size_price'], 2); ?></p>
                                                                    <p class="mb-1"><strong>Cost:</strong> ₱<?= number_format($size['product_size_cost'], 2); ?></p>

                                                                    <!-- Input field for quantity and the "Add" button -->
                                                                    <div class="d-flex align-items-center">
                                                                        <input type="number" name="quantity" class="form-control me-2" min="1" value="1" style="width: 70px;">
                                                                        <!-- Add hidden fields to send product size id and price -->
                                                                        <input type="hidden" name="product_size_id" value="<?= $size['product_size_id']; ?>">
                                                                        <input type="hidden" name="product_size_price" value="<?= $size['product_size_price']; ?>">
                                                                        <button class="btn btn-success" type="submit" name="add_to_cart">Add</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    <?php endforeach; ?>
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
                                                        <?php foreach ($sizes as $size): ?>
                                                            <form action="/AmitieCafe/actions/cart.php" method="post">
                                                                <p><strong>Description:</strong> <?= htmlspecialchars($product['product_desc']); ?></p>
                                                                <div class="list-group">
                                                                    <!-- Loop through each size and display its price, cost, and an input field for quantity -->

                                                                    <div class="list-group-item list-group-item-action p-3 mb-2 bg-light rounded">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <h6 class="mb-1"><strong>Size:</strong> <?= htmlspecialchars($size['product_size']); ?></h6>
                                                                        </div>
                                                                        <p class="mb-1"><strong>Price:</strong> ₱<?= number_format($size['product_size_price'], 2); ?></p>
                                                                        <p class="mb-1"><strong>Cost:</strong> ₱<?= number_format($size['product_size_cost'], 2); ?></p>

                                                                        <!-- Input field for quantity and the "Add" button -->
                                                                        <div class="d-flex align-items-center">
                                                                            <input type="number" name="quantity" class="form-control me-2" min="1" value="1" style="width: 70px;">
                                                                            <!-- Add hidden fields to send product size id and price -->
                                                                            <input type="hidden" name="product_size_id" value="<?= $size['product_size_id']; ?>">
                                                                            <input type="hidden" name="product_size_price" value="<?= $size['product_size_price']; ?>">
                                                                            <button class="btn btn-success" type="submit" name="add_to_cart">Add</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        <?php endforeach; ?>
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
                <?php
                $stmt = $pdo->prepare("SELECT c.cart_id, c.cart_price, c.cart_qty, c.cart_total,
                                            p.product_name, ps.product_size, c.product_size_id
                                            FROM tblcart c
                                            JOIN tblproductsize ps ON c.product_size_id = ps.product_size_id
                                            JOIN tblproduct p ON ps.product_id = p.product_id
                                            ORDER BY c.cart_id DESC");
                $stmt->execute();
                $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <?php if (count($cartItems) > 0): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <form action="/AmitieCafe/actions/addorsub-quantity.php" method="POST">
                                    <strong><?= htmlspecialchars($item['product_name']) ?> x <?= htmlspecialchars($item['product_size']) ?></strong><br>
                                    <strong>
                                        Price: ₱<?= htmlspecialchars($item['cart_price']) ?><br>
                                        Qty: <?= $item['cart_qty'] ?>

                                        <!-- Buttons -->
                                        <button class="btn btn-primary btn-sm" type="submit" name="add">+</button>
                                        <button class="btn btn-danger btn-sm" type="submit" name="sub">-</button>
                                    </strong>

                                    <!-- ✅ Hidden fields (must be inside the form) -->
                                    <input type="hidden" name="product_size_id" value="<?= $item['product_size_id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="product_size_price" value="<?= $item['cart_price'] ?>">
                                </form>
                            </div>
                            <span class="badge bg-success rounded-pill">
                                ₱<?= number_format($item['cart_total'], 2) ?>
                            </span>
                        </li>
                    <?php endforeach; ?>

                <?php else: ?>
                    <li class="list-group-item text-center text-muted">Cart is empty.</li>
                <?php endif; ?>

            </ul>

            <div class="d-flex justify-content-between mb-2">
                <?php
                $stmt = $pdo->prepare("SELECT SUM(cart_total) AS total_cart
                                            FROM tblcart");
                $stmt->execute();
                $totalCart = $stmt->fetch();
                ?>
                <h3 class="fw-bold">Total:</h3>
                <h3 class="fw-bold" id="totalPrice">₱<?= $totalCart['total_cart']; ?></h3>
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
    let calcDisplay = document.getElementById('calc-display');
    let currentInput = '';

    function handleCalc(value) {
        if (value === 'C') {
            currentInput = '';
        } else if (value === '=') {
            try {
                currentInput = eval(currentInput).toString();
            } catch (e) {
                currentInput = 'Error';
            }
        } else {
            currentInput += value;
        }

        calcDisplay.value = currentInput;
    }
</script>

<?php include "includes/footer.php"; ?>