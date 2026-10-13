<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); 
include("../includes/connection.php"); 
include("../includes/header.php"); 
include("../auth/check_login.php"); 

$role = $_SESSION['role'] ?? ''; 
$user_id = $_SESSION['user_id'] ?? ''; 

if (!in_array($role, ['admin','staff'])) {
    header("Location: ../auth/login.php"); 
    exit;
     }

    if (isset($_GET['export']) && $_GET['export'] === 'excel') {

  header("Content-Type: text/csv");
  header("Content-Disposition: attachment; filename=sales_report.csv");
  $output = fopen("php://output", "w");

// HEADER ROW
fputcsv($output, ['Product', 'Quantity', 'Total Price', 'Sold By', 'Date']);

  $where = "1";
  if ($role === 'staff') {
      $where = "sales.sold_by = '$user_id'";
  }

  $sql = "
    SELECT 
      products.product_name,
      sales.quantity,
      sales.total_price,
      users.username,
      sales.sale_date
    FROM sales
    JOIN products ON sales.product_id = products.product_id
    JOIN users ON sales.sold_by = users.user_id
    WHERE $where
  ";

  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    echo "{$row['product_name']}\t{$row['quantity']}\t{$row['total_price']}\t{$row['username']}\t{$row['sale_date']}\n";
  }

  exit; // MUHIIM
}

$where = "1";

if ($role === 'staff') {
  $where .= " AND sales.sold_by = '$user_id'";
}

if (!empty($_GET['type'])) {
  if ($_GET['type'] == 'daily') {
    $where .= " AND DATE(sales.sale_date) = CURDATE()";
  } elseif ($_GET['type'] == 'weekly') {
    $where .= " AND YEARWEEK(sales.sale_date,1) = YEARWEEK(CURDATE(),1)";
  } elseif ($_GET['type'] == 'monthly') {
    $where .= " AND MONTH(sales.sale_date) = MONTH(CURDATE())
                AND YEAR(sales.sale_date) = YEAR(CURDATE())";
  }
}

if (!empty($_GET['from']) && !empty($_GET['to'])) {
  $from = $_GET['from'];
  $to   = $_GET['to'];
  $where .= " AND DATE(sales.sale_date) BETWEEN '$from' AND '$to'";
}

$sql = "
SELECT 
  sales.sale_id,
  sales.sale_date,
  sales.quantity,
  sales.total_price,
  products.product_name,
  users.username
FROM sales
JOIN products ON sales.product_id = products.product_id
JOIN users ON sales.sold_by = users.user_id
WHERE  $where
ORDER BY sales.sale_date DESC
";

$result = mysqli_query($conn, $sql);

/* totals */
$total_qty = 0;
$total_value = 0;


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sales Report</title>
</head>
<body>

<main class="app-main">

  <!-- Header -->
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Sales Report</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="../admin/admin_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Sales Report</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="app-content">
    <div class="container-fluid">

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Sales Summary</h5>
          <div class="col-md-12 text-end">
              <a href="export_sales_report.php?export=excel" class="btn btn-success"> Export Excel</a>
              <a href="sales_report_print.php" target="_blank" class="btn btn-secondary"> Print</a>
          </div>
        </div>

        <div class="card-body table-responsive">

          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Sold By</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
            

            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
                <?php 
                $total_qty += $row['quantity']; 
                $total_value += $row['total_price']; 
                ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($row['product_name']) ?></td>
                  <td><?= $row['quantity'] ?></td>
                  <td>$<?= number_format($row['total_price'],2) ?></td>
                  <td><?= htmlspecialchars($row['username']) ?></td>
                  <td><?= date("d M Y", strtotime($row['sale_date'])) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center text-muted">
                  No sales records found
                </td>
              </tr>
            <?php endif; ?>

            </tbody>
          </table>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <strong>Total Quantity:</strong> <?= $total_qty ?>
            </div>
            <div class="col-md-6 text-end">
              <strong>Total Value:</strong> $<?= number_format($total_value,2) ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</main>
</body>
</html>

<?php include "../includes/footer.php"; ?>
