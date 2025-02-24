<?php

$password = "admin";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "Password Hashed: " . $hashed_password;

?>