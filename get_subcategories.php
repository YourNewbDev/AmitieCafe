<?php

include "includes/header.php";

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$stmt = $pdo->prepare("SELECT subcategory_id, subcategory_name FROM tblsubcategory WHERE category_id = ?");
$stmt->execute([$category_id]);
$subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($subcategories);
