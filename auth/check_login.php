<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: /sms/auth/login.php");
    exit();
}
?>