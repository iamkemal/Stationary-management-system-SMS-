<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ❌ delete session
session_unset();
session_destroy();

// ❌ delete cookies
setcookie("user_id", "", time() - 3600, "/");
setcookie("user_email", "", time() - 3600, "/");

// redirect login
header("Location: ../auth/login.php");
exit;


?>
