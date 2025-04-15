<?php

if (!defined('BASE_URL')) {

// Detect protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

// Get domain (localhost or live domain)
$domain = $_SERVER['HTTP_HOST'];

// Manually set the project root (AmitieCafe) from DOCUMENT_ROOT
$projectFolder = "/AmitieCafe"; // Change this if your folder name is different

// Define the Base URL correctly
define("BASE_URL", $protocol . $domain . $projectFolder . "/");
}
?>
