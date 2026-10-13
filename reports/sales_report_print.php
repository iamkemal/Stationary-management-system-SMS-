<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

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
ORDER BY sales.sale_date DESC
";
$result = mysqli_query($conn, $sql);

$total_qty = 0;
$total_value = 0;
                
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Report</title>
<style>
body { font-family: Arial; }
table { width:100%; border-collapse:collapse; }
th,td { border:1px solid #000; padding:6px; font-size:12px; }
h3 { text-align:center; }
</style>
</head>
<body onload="window.print()">

<h3>Sales Report</h3>
<p>Date: <?= date('Y-m-d') ?></p>

<table>
<tr>
  <th>Product</th>
  <th>Qty</th>
  <th>Total</th>
  <th>Sold By</th>
  <th>Date</th>
</tr>
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <?php 
$total_qty += $row['quantity'];
$total_value += $row['total_price'];
?>
<tr>
  <td><?= $row['product_name'] ?></td>
  <td><?= $row['quantity'] ?></td>
  <td><?= $row['total_price'] ?></td>
  <td><?= $row['username'] ?></td>
  <td><?= $row['sale_date'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<hr>

<div style="width:100%; margin-top:10px;">
  <div style="float:left;">
    <strong>Total Quantity:</strong> <?= $total_qty ?>
  </div>

  <div style="float:right;">
    <strong>Total Value:</strong> $<?= number_format($total_value,2) ?>
  </div>

  <div style="clear:both;"></div>
</div>

</body>
</html>