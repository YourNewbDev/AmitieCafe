<?php

session_start();
$success_message = "You have successfully logged out!";

session_unset();
session_destroy();

session_start();
$_SESSION['success_message'] = $success_message;


header("Location: login.php");

exit;

?>