<?php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../actions/auth.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT  FROM tblcart");
