<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    die("Invalid request");
}

$student_id = $_GET['student_id']; 

$stmt = $conn->prepare(
    "DELETE FROM students WHERE student_id = ?"
);
$stmt->bind_param("s", $student_id);

if ($stmt->execute() && $stmt->affected_rows === 1) {
    header("Location: manage_student.php?msg=deleted");
    exit();
} else {
    echo "Delete failed or no record found";
}


$stmt->close();
$conn->close();
?>
