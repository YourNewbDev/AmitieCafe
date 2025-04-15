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
        $stmt = $pdo->prepare("SELECT * FROM tblproduct WHERE subcategory_id = ?");
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
            <div class="scrollable-menu pt-2 me-5">
                <form action="/AmitieCafe/pos.php" method="GET">
                    <a href="/AmitieCafe/pos.php" class="btn custom-btn btn-lg rounded-top m-2" name="category_id" value="<?= (int)$category['category_id'] ?>">
                        ALL
                    </a>
                    <?php foreach ($categories as $category) : ?>
                        <button class="btn custom-btn btn-lg rounded-top m-2" name="category_id" value="<?= (int)$category['category_id'] ?>" type="submit">
                            <?= htmlspecialchars($category['category_name']); ?>
                        </button>
                    <?php endforeach; ?>
                </form>
            </div>

            <?php if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['category_id'])): ?>
                <?php
                $category_id = (int) $_GET['category_id'];
                $stmt = $pdo->prepare("SELECT * FROM tblsubcategory WHERE category_id = ?");
                $stmt->execute([$category_id]);
                $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <!-- Subcategory Buttons -->
                <form action="/AmitieCafe/pos.php" method="GET">
                    <input type="hidden" name="category_id" value="<?= $category_id; ?>">
                    <?php foreach ($subcategories as $subcategory) : ?>
                        <h4 class="mt-4 text-primary"><?= htmlspecialchars($subcategory['subcategory_name']); ?></h4>
                        <button class="btn custom-btn rounded-top m-2 w-25" name="subcategory_id" value="<?= (int)$subcategory['subcategory_id']; ?>">
                            <?= htmlspecialchars($subcategory['subcategory_name']); ?>
                        </button>
                    <?php endforeach; ?>
                </form>

                <?php if (isset($_GET['subcategory_id'])): ?>
                    <?php
                    $subcategory_id = (int) $_GET['subcategory_id'];
                    $stmt = $pdo->prepare("SELECT * FROM tblproduct WHERE subcategory_id = ?");
                    $stmt->execute([$subcategory_id]);
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="row">
                        <div class="col">
                            <?php foreach ($products as $product): ?>
                                <button class="btn custom-btn m-2 w-25">
                                    <?= htmlspecialchars($product['product_name']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

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
                                        <button class="btn custom-btn m-2 w-25">
                                            <?= htmlspecialchars($product['product_name']); ?>
                                        </button>
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

        <!-- Right Sidebar -->
        <aside class="col-md-5 d-flex flex-column border-start vh-100 px-3">
            <h4 class="fw-bold text-center">CURRENT SALE</h4>

            <ul class="list-group overflow-auto flex-grow-1 mb-3">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="text-truncate w-75">Product Name</div>
                    <span class="badge bg-primary rounded-pill">₱80.00</span>
                </li>
            </ul>

            <div class="d-flex justify-content-between mb-2">
                <h3 class="fw-bold">Total:</h3>
                <h3 class="fw-bold">₱384.00</h3>
            </div>

            <button class="btn btn-success w-100 mb-3">Complete Sale</button>

            <!-- Calculator -->
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
    document.addEventListener("DOMContentLoaded", function() {
        let calcDisplay = document.getElementById("calc-display");
        let calcExpression = "";

        window.handleCalc = function(value) {
            if (value === "C") {
                calcExpression = "";
            } else if (value === "=") {
                try {
                    calcExpression = eval(calcExpression).toString();
                } catch (e) {
                    calcExpression = "Error";
                }
            } else {
                calcExpression += value;
            }
            calcDisplay.value = calcExpression;
        };
    });
</script>

<?php include "includes/footer.php"; ?>