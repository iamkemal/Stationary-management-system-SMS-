<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

// Get Stock Data
$query = "SELECT product_id, product_name, stock, price FROM products ORDER BY product_name ASC";
$result = mysqli_query($conn, $query);

$total_quantity = 0;
$total_stock_value = 0;

$settings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT low_stock_limit FROM system_settings LIMIT 1"));
$limit = $settings['low_stock_limit'];



?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Stock Summary</title>
    </head>
    <body>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">stock summary report</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">
                                <a href="<?php echo $base_url; ?>/admin/admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">stock summary</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
       <div class="app-content">
       <div class="container-fluid">
        <div class="card">
         <div class="card-header">
                <h5>Stock Summary</h5>
                <div class="col-md-4s text-end">
                    <a href="export_stock_summary.php" class="btn btn-success"> Export Excel</a>
              <a href="stock_summary_print.php" target="_blank" class="btn btn-secondary"> Print</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 1;
                    while($row = mysqli_fetch_assoc($result)) {

                        $total_value = $row['stock'] * $row['price'];
                        $total_quantity += $row['stock'];
                        $total_stock_value += $total_value;

                        
                    ?>
                            <tr class="<?= ($row['stock'] <= $limit) ? 'table-danger' : ''; ?>"
                            title="<?= ($row['stock'] <= $limit) ? 'Low Stock alert - Quantity is below the minimum limit' : ''; ?>">
                            <td><?= $count++; ?></td>
                            <td><?= $row['product_name']; ?></td>
                            <td><?= $row['stock']; ?></td>
                            <td><?= number_format($row['price'],2); ?></td>
                            <td><?= number_format($total_value,2); ?></td>
                        </tr>
        
                    <?php } ?>
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Total Quantity:</strong> <?= $total_quantity; ?>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Total Stock Value:</strong> <?= number_format($total_stock_value,2); ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
    </main>
    </body>
</html>

<?php include("../includes/footer.php"); ?>