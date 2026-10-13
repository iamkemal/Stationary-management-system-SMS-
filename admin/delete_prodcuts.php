<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);


if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    die("Invalid request");
}

$product_id = $_GET['product_id'];

$stmt = $conn->prepare(
    "DELETE FROM products WHERE product_id = ?"
);
$stmt->bind_param("s", $product_id);

if ($stmt->execute() && $stmt->affected_rows === 1) {
    header("Location: manage_products.php?msg=deleted");
    exit();
} else {
    echo "Delete failed or no record found";
}

$stmt->close();
$conn->close();
?>