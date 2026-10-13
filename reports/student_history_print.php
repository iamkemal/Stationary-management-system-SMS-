<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../auth/middlewar.php");

checkRole(['admin','staff','special member']);

$student_id=$_GET['student_id']??'';

$where="1";
if($student_id!=""){
$where.=" AND students.student_id='$student_id'";
}

$sql="SELECT students.student_name,products.product_name,
sales.quantity,sales.total_price,sales.sale_date
FROM sales
JOIN students ON sales.student_id=students.student_id
JOIN products ON sales.product_id=products.product_id
WHERE $where";

$result=mysqli_query($conn,$sql);

/* totals */
$total_qty = 0;
$total_value = 0;
?>

<!DOCTYPE html>
<html>
    <head></head><title>Student History</title>
    <style>
    body{font-family:Arial}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid #000;padding:6px;font-size:12px}
    h3{text-align:center}
    </style>
    </head>
    <body onload="window.print()">
         <h3>Student Purchase History</h3>
         <p>Date: <?= date('Y-m-d') ?></p>
         <table>
            <tr>
                <th>Student</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>
            <?php while($row=mysqli_fetch_assoc($result)){ ?>
            <?php 
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