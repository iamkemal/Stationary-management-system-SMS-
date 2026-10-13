<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "New user added successfully!";
} else if (isset($_GET['error'])) {
    $error = "Error: ".mysqli_error($conn);
}

if(isset($_POST["submit"])){

$full_name = $_POST["full_name"];
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$role = $_POST["role"];

/* PASSWORD HASH */

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


/* INSERT USER */

 $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $username, $email, $hashed_password, $role);

    if($stmt->execute()){
        header("Location: add_user.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Dashboard link based on role
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $dashboard_link = "../admin/admin_dashboard.php";
} elseif ($role === 'staff') {
    $dashboard_link = "../staff/staff_dashboard.php";
} else {
    $dashboard_link = "../auth/login.php";
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add User</title>
    </head>
    <body>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Add User</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item">
                                    <a href="admin_dashboard.php">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Add User</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Register New User</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
                            <?php if (isset($success)) echo '<div class="alert alert-success">'.$success.'</div>'; ?>
                            <form action="" method="POST">
                                <!-- ROW 1 -->
                                 <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" placeholder="Enter full name">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="Enter username">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter email">
                                    </div>
                                </div>
                                <!-- ROW 2 -->
                                 <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="role">
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                        <option value="special member">Special Member</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary col-md-12" name="submit" type="submit">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php include("../includes/footer.php"); ?>