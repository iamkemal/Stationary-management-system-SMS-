<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

 
if (isset($_GET['id'])) {
   // $user_id = intval($_GET['id']);  
   $user_id = $_GET['id'];

    
    $delete_query = "DELETE FROM users WHERE user_id = $user_id";

    if (mysqli_query($conn, $delete_query)) {
     
        header("Location: manage_user.php?msg=deleted"); 
        exit();
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}

mysqli_close($conn);
?>
 