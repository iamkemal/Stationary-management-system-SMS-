<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin','staff','special member']);

$student_id = $_GET['student_id'] ?? '';

$where = "1";
if($student_id != ""){
    $where .= " AND students.student_id='$student_id'";
}

$sql = "
SELECT 
students.student_name,
products.product_name,
sales.quantity,
sales.total_price,
users.username,
sales.sale_date

FROM sales
JOIN students ON sales.student_id = students.student_id
JOIN products ON sales.product_id = products.product_id
JOIN users ON sales.sold_by = users.user_id

WHERE $where
ORDER BY sales.sale_date DESC
";

$result = mysqli_query($conn,$sql);

/* totals */
$total_qty = 0;
$total_value = 0;

/* students dropdown */
$students = mysqli_query($conn,"SELECT * FROM students ORDER BY student_name ASC");

//Dashboard Link based on role
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $dashboard_link = "../admin/admin_dashboard.php";
} elseif ($role === 'staff') {
    $dashboard_link = "../staff/staff_dashboard.php";
} elseif ($role === 'special member') {
    $dashboard_link = "../special member/sp_member_dashboard.php";
} else {
    $dashboard_link = "../auth/login.php";
}


?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Student History Report</title>
    </head>
    <body>
        <main class="app-main">
            <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Student History Report</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">
                                <a href="<?php echo $dashboard_link; ?>">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Student History Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <form method="GET" class="row g-2" autocomplete="off">
                                <div class="col-md-4 col-lg-3 col-sm-6">
                                    <select name="student_id" class="form-control">
                                        <option value="">All Students</option>
                                        <?php while($s = mysqli_fetch_assoc($students)){ ?>
                                        <option value="<?= $s['student_id'] ?>" 
                                        <?= ($student_id==$s['student_id'])?'selected':'' ?>>
                                        <?= $s['student_name'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-lg-1 col-sm-6">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                            <div class="col-md-4s text-end">
                                <a href="export_student_history.php" class="btn btn-success"> Export Excel</a>
                                <a href="student_history_print.php" target="_blank" class="btn btn-secondary"> Print</a>
                            </div>
                        </form> 
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                    <th>Sold By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                        <tbody>
                            <?php
                            $count = 1;
                            while($row = mysqli_fetch_assoc($result)){
                                $total_qty += $row['quantity'];
                                $total_value += $row['total_price'];
                                ?>
                                <tr>

                            <td><?= $count++ ?></td>
                            <td><?= $row['student_name'] ?></td>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>$<?= number_format($row['total_price'],2) ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['sale_date'] ?></td>
                        </tr>
                        <?php } ?>
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

<?php include("../includes/footer.php"); ?>