<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function checkRole($allowed_roles=[]){

    if(!isset($_SESSION['user_id'])){
        header("Location: ../login.php");
        exit();
    }

    if(!in_array($_SESSION['role'],$allowed_roles)){
        header("Location: ../errors/access_denied.php");
        exit();
    }

}

?>