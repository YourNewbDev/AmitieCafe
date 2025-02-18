<?php

$server = "localhost";
$dbname = "dbcoffeeshop";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$server;dbname=$dbname;", $username, $password);

    //set the PDO error mode to exception
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $err) {
    echo "Connection failed: " . $err->getMessage(); 
}

?>