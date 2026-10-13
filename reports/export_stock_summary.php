<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']); // stock summary admin only

// Excel headers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=stock_summary.xls");

// QUERY (la mid stock_summary.php)
$sql = "SELECT product_name, stock, price FROM products ORDER BY product_name ASC";

$result = mysqli_query($conn, $sql);

// TOTALS
$total_quantity = 0;
$total_stock_value = 0;
?>

<table border="1">
<tr>
<th>#</th>
<th>Product Name</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Total Value</th>
</tr>

<?php 
$count = 1;

if(mysqli_num_rows($result) > 0):
while($row = mysqli_fetch_assoc($result)):

$total_value = $row['stock'] * $row['price'];

$total_quantity += $row['stock'];
$total_stock_value += $total_value;
?>

  <tr>
    <td><?= $count++; ?></td>
    <td><?= htmlspecialchars($row['product_name']); ?></td>
    <td><?= $row['stock']; ?></td>
    <td><?= number_format($row['price'],2); ?></td>
    <td><?= number_format($total_value,2); ?></td>
  </tr>

<?php endwhile; else: ?>
  <tr>
    <td colspan="5" align="center">No products found</td>
  </tr>
<?php endif; ?>

    <!-- TOTAL ROW -->
   <tr>
    <td colspan="2"><strong>Total</strong></td>
    <td><strong><?= $total_quantity; ?></strong></td>
    <td></td>
    <td><strong><?= number_format($total_stock_value,2); ?></strong></td>
</tr>

</table>