<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin','staff']);

$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';

// WHERE condition
$where = "1";

if ($role === 'staff') {
    $where .= " AND sales.sold_by = '$user_id'";
}

// Excel headers
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_report.xls");

// SQL
$sql = "SELECT 
    students.student_name,
    products.product_name,
    sales.quantity,
    sales.total_price,
    sales.sale_date
FROM sales
JOIN students ON sales.student_id = students.student_id
JOIN products ON sales.product_id = products.product_id
WHERE $where
ORDER BY sales.sale_date DESC";

$result = mysqli_query($conn, $sql);

// totals
$total_qty = 0;
$total_value = 0;
?>
<table border="1"> 
    <tr>
        <th>Student</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Total Price</th>
        <th>Date</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($result)){ 

        $total_qty += $row['quantity'];
        $total_value += $row['total_price'];

?>

    <tr>
        <td><?= $row['student_name'] ?></td>
        <td><?= $row['product_name'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['total_price'] ?></td>
        <td><?= $row['sale_date'] ?></td>
    </tr>

<?php } ?>

    <!-- TOTAL ROW -->
     <tr>
        <td colspan="2"><strong>Total</strong></td>
        <td><strong><?= $total_qty ?></strong></td>
        <td><strong><?= $total_value ?></strong></td>
        <td></td>
    </tr>
</table>