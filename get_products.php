<?php
require 'includes/db.php';

$subcategory_id = isset($_GET['subcategory_id']) ? (int)$_GET['subcategory_id'] : 0;

$stmt = $pdo->prepare("SELECT product_id, product_name FROM tblproduct WHERE subcategory_id = ?");
$stmt->execute([$subcategory_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
