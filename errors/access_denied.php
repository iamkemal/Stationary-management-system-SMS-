<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Access Denied</title>
<style>

body{
    font-family: Arial;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:40px;
    text-align:center;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

h1{
    color:#dc3545;
}

a{
    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    background:#007bff;
    color:white;
    text-decoration:none;
    border-radius:5px;
}
button{
    margin-top:15px;
    padding:10px 20px;
    background:#007bff;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
button:hover{
    background:#0056b3;
}

</style>

</head>
<body>
    <div class="box">
        <h1>Access Denied</h1>
        <p>You do not have permission to access this page.</p>
        <a href="javascript:history.back()">Go Back</a>
    </div>
</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</html>