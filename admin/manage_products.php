<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin','staff']);


/* FILTER PRODUCT NAME */

$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : '';


if (!empty($product_name)) {
    $query = "SELECT * FROM products WHERE product_name = '$product_name'";
    } else {
        $query = "SELECT * FROM products";
        }
        $result = mysqli_query($conn, $query);

/* OPTIONS FOR FILTER */
$product_options = mysqli_query($conn, "SELECT DISTINCT product_name FROM products");

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
        <title>Manage Products</title>
    </head>
    <body>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Manage Products</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item">
                                    <a href="<?= $dashboard_link ?>">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Manage Products</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <!-- FILTER FORM -->
                    <div class="card mb-4">
                        <div class="card-header">
                         <h5 class="card-title">Filter Products</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-2 align-items-end" action="" autocomplete="off">
                                <div class="col-md-4 col-lg-3 col-sm-6">
                                    <label for="product_name" class="form-label
                                <label for="product_name">Filter by Name:</label>
                                <select name="product_name" class="form-select">
                                    <option value="">All Products</option>
                                    <?php while ($row = mysqli_fetch_assoc($product_options)): ?>
                                        <option value="<?php echo htmlspecialchars($row['product_name']); ?>"
                                        <?php if ($product_name == $row['product_name']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($row['product_name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                </div>
                                <div class="col-md-2 col-lg-1 col-sm-6 d-flex allign-items-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- PRODUCTS TABLE -->
                     <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Registered Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <?php if ($role === 'admin'): ?>
                                                <th>Action</th>
                                                <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($result) > 0): ?>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                    <td>$<?php echo htmlspecialchars($row['price']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                                    <?php if ($role === 'admin'): ?>
                                                        <td><a href="../admin/edit_products.php?product_id=<?= $row['product_id']; ?>" class="btn btn-warning">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="../admin/delete_prodcuts.php?product_id=<?= $row['product_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"> 
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="6">
                                                            No products found.
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
<?php include '../includes/footer.php'; ?>