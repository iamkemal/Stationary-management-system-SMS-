<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin','staff','special member']);

$email = $_SESSION['email']; 

// GET CURRENT USER DATA
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($user_query);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';
$email = $_SESSION['email'] ?? '';
 

// GET SETTINGS
$settings_query = mysqli_query($conn, "SELECT * FROM system_settings LIMIT 1");
$settings = mysqli_fetch_assoc($settings_query);


/* =======================
   CHANGE PASSWORD
======================= */
if (isset($_POST['change_password'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Check current password
    if (password_verify($current_password, $user['password'])) {
        
        $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $update = mysqli_query($conn, "
            UPDATE users 
            SET password='$new_hashed' 
            WHERE email='$email'
        ");

        if ($update) {
            $success = "Password updated successfully!";
        } else {
            $error = "Failed to update password!";
        }

    } else {
        $error = "Current password is incorrect!";
    }
}


/* =======================
   SALES SETTINGS
======================= */
if (isset($_POST['update_sales'])) {

    $low_stock = $_POST['low_stock'];

    // Check if settings exist
    if ($settings) {
        $update = mysqli_query($conn, "
            UPDATE system_settings 
            SET low_stock_limit='$low_stock'
        ");
    } else {
        $update = mysqli_query($conn, "
            INSERT INTO system_settings (low_stock_limit) 
            VALUES ('$low_stock')
        ");
    }

    if ($update) {
        $success = "Sales settings updated successfully!";
        
        // refresh settings
        $settings_query = mysqli_query($conn, "SELECT * FROM system_settings LIMIT 1");
        $settings = mysqli_fetch_assoc($settings_query);

    } else {
        $error = "Failed to update settings!";
    }
}

// DASHBOARD LINK BASED ON ROLE
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $dashboard_link = "../admin/admin_dashboard.php";
} elseif ($role === 'staff') {
    $dashboard_link = "../staff/staff_dashboard.php";
} elseif ($role === 'special member') {
    $dashboard_link = "../special member/sp_member_dashboard.php";
} else {
    $dashboard_link = "../auth/login.php";
}

?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Settings</title>
    </head>
    <body>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Settings</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">
                                <a href="<?php echo $dashboard_link; ?>">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <?php if(isset($success)) { ?>
                <div class="alert alert-success"><?= $success; ?></div>
                <?php } elseif(isset($error)) { ?>
                <div class="alert alert-danger"><?= $error; ?></div>
                <?php } ?>
        <!-- CHANGE PASSWORD -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Change Password</h5>
            </div>
        <div class="card-body">
            <form method="POST" autocomplete="off">
                <input type="hidden" name="change_password" value="1">
                <div class="mb-3">
                    <label><b>Current Password*</b></label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label><b>New Password*</b></label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger">Change Password</button>
            </form>
        </div>
        </div>

        <!-- SALES SETTINGS -->
         <?php if ($role === 'admin'): ?>
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Sales Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="update_sales" value="1">
                    <div class="mb-3">
                        <label><b>Low Stock Alert Limit*</b></label>
                        <input type="number" name="low_stock" class="form-control" value="<?= $settings['low_stock_limit'] ?? 5 ?>" autocomplete="off" required>
                    </div>
                    <button type="submit" class="btn btn-success">Save Sales Settings</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
        </div>
        </div>
    </main>
</body>
</html>
<?php 
 include '../includes/footer.php';  
 ?>