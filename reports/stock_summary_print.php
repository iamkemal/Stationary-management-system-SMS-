<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

/* low stock limit */
$settings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT low_stock_limit FROM system_settings LIMIT 1"));
$limit = $settings['low_stock_limit'] ?? 5;

/* stock data */
$sql = "SELECT product_name, stock, price FROM products ORDER BY product_name ASC";
$result = mysqli_query($conn,$sql);

$total_qty = 0;
$total_value = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Stock Summary Report</title>

<style>
body { font-family: Arial; }
table { width:100%; border-collapse:collapse; }
th,td { border:1px solid #000; padding:6px; font-size:12px; }
h3 { text-align:center; }
</style>
</head>
<body onload="window.print()">
    <h3>Stock Summary Report</h3>
    <p>Date: <?= date('Y-m-d') ?></p>
    <table>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Value</th>
        </tr>
    <?php
    $count = 1;
    while($row = mysqli_fetch_assoc($result)):?>
    <?php 
        $total_qty += $row['stock'];
        $total_value += $row['stock'] * $row['price'];
    ?>
        <tr class="<?= ($row['stock'] <= $limit) ? 'low-stock' : '' ?>">
            <td><?= $count++ ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['stock'] ?></td>
            <td>$<?= number_format($row['price'],2) ?></td>
            <td>$<?= number_format($total_value,2) ?></td>
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