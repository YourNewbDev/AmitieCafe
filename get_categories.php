<?php

include "includes/header.php";

$stmt = $pdo->prepare("SELECT * FROM tblcategory");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($categories);
