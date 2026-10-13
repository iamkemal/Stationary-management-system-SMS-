<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);


$role = isset($_GET['role']) ?  $_GET['role'] : '';

if ($role !== '') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
}
    

/* USER OPTIONS FOR FILTER */
$user_options = mysqli_query($conn,"SELECT DISTINCT role FROM users");
?>
 
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Users</title>
    </head>
    <body>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Manage Users</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item">
                                    <a href="admin_dashboard.php">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Manage Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <!-- FILTER CARD -->
                     <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Filter Users</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-2 align-items-end" action="" autocomplete="off">
                                <div class="col-md-4">
                                <label>Filter by Role:</label>
                                <select name="role" class="form-select">
                                    <option value="" <?= ($role == '') ? 'selected' : '' ?>>All Users</option>
                                    <?php while($row=mysqli_fetch_assoc($user_options)): ?>
                                        <option value="<?php echo htmlspecialchars($row['role']); ?>"
                                        <?php if($role==$row['role']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- USERS TABLE -->
                     <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">System Users</h5>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($result)>0): ?>
                                        <?php while($row=mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['role']); ?></td>

                                                <td><a href="edit_user.php?user_id=<?= $row['user_id']; ?>" class="btn btn-warning">
                                                    <i class="bi bi-pencil-square"></i></a>

                                                    <a href="delete_user.php?user_id=<?= $row['user_id']; ?>" class="btn btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6">
                                                        No users found.
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
<?php include("../includes/footer.php"); ?>