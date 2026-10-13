<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");

if(!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])){
    
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['email'] = $_COOKIE['user_email'];

    header("Location: ../admin/admin_dashboard.php" ); 
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){

$user = $result->fetch_assoc();

if(password_verify($password,$user['password'])){

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['email'] = $user['email'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

if(isset($_POST['remember'])){
    setcookie("user_email", $user['email'], time() + (86400 * 30), "/");
    setcookie("user_id", $user['user_id'], time() + (86400 * 30), "/");
}

if ($user['role'] === 'admin'){
    header("Location: ../admin/admin_dashboard.php");
    }
    elseif ($user['role'] === 'staff'){
        header("Location: ../staff/staff_dashboard.php");
        }
        elseif ($user['role'] === 'special member'){
            header("Location: ../special member/sp_member_dashboard.php");
            }
            exit;
            }
            else{
                $error = "Invalid email or password";
                }
                }
                else {
                    $error = "Invalid email or password";
                    }
                    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Stationery System</title>
    <link rel="icon" href="../assets/img/brandlogo.png">
    <link rel="stylesheet" href="../css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
    body{
        background:#f4f6f9;
        font-family: 'Segoe UI', sans-serif;
    }
    .login-page{
        height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .login-card{
        width:380px;
        background:white;
        border-radius:10px;
        box-shadow:0 10px 30px rgba(0,0,0,0.1);
        padding:35px;
    }
    .logo{
        width:90px;
        display:block;
        margin:auto;
        margin-bottom:15px;
    }
    .system-title{
        text-align:center;
        font-weight:600;
        margin-bottom:25px;
    }
    .input-group-text{
        background:#f1f1f1;
    }
    .login-btn{
        font-weight:600;
    }
    .alert{
        text-align:center;
    }
    .footer-text{
        text-align:center;
        margin-top:15px;
        font-size:13px;
        color:#777;
    }
    </style>
    </head>
    <body>
        <div class="login-page">
            <div class="login-card">
                <img src="../assets/img/brandlogo.png" class="logo">
                <h4 class="system-title">S M S</h4>
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST" autocomplete="off" >
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="text" name="email" class="form-control" placeholder="Enter email" autocomplete="email" value="<?= $_COOKIE['user_email'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label"  for="remember" > Remember me </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary login-btn">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>
                    <div class="footer-text">
                        © <?= date("Y") ?> Stationery System
                    </div>
                </div>
            </div>
        </body>
    </html>
