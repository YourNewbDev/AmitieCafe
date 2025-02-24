<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../actions/login.php");
    exit;
}

if (isset($required_role) && $_SESSION['user_role'] !== $required_role) {
    header("Location: ../error/no-access.php");
    exit;
}

?>