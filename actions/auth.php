<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', "/AmitieCafe/");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ". BASE_URL ."actions/login.php");
    exit;
}

if (isset($required_role) && $_SESSION['user_role'] !== $required_role) {
    header("Location: ". BASE_URL ."error/no-access.php");
    exit;
}

?>