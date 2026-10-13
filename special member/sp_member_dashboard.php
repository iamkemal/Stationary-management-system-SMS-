<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['special member']);

/* TOTAL PRODUCTS */
$total_products = $conn->query("
SELECT COUNT(*) AS total
FROM products
")->fetch_assoc()['total'];

/* TOTAL SALES */
$total_sales = $conn->query("
SELECT COUNT(*) AS total
FROM sales
")->fetch_assoc()['total'];


/* TOP SELLING ITEM */
$top_item = $conn->query("
SELECT p.product_name, SUM(s.quantity) as total_sold
FROM sales s
JOIN products p ON s.product_id=p.product_id
GROUP BY s.product_id
ORDER BY total_sold DESC
LIMIT 1
")->fetch_assoc();


/* LOWEST SELLING ITEM */
$low_item = $conn->query("
SELECT p.product_name, SUM(s.quantity) as total_sold
FROM sales s
JOIN products p ON s.product_id=p.product_id
GROUP BY s.product_id
ORDER BY total_sold ASC
LIMIT 1
")->fetch_assoc();

/* SALES BY DEPARTMENT */
$dept_labels=[];
$dept_sales=[];

$dept=$conn->query("
SELECT st.department,
SUM(s.total_price) as total
FROM sales s
JOIN students st ON s.student_id=st.student_id
GROUP BY st.department
order by total desc
");

while($row=$dept->fetch_assoc()){
$dept_labels[]=$row['department'];
$dept_sales[]=$row['total'];
}


/* =============================
SALES BY SEMESTER
============================= */
$sem_labels=[];
$sem_sales=[];

$sem=$conn->query("
SELECT st.semester,
SUM(s.total_price) as total
FROM sales s
JOIN students st ON s.student_id=st.student_id
GROUP BY st.semester
order by total desc
");

while($row=$sem->fetch_assoc()){
$sem_labels[]=$row['semester'];
$sem_sales[]=$row['total'];
}




?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Special Member Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <h3>Special Member Dashboard</h3>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
        
        <div class="row">
        <!-- PRODUCTS -->
         <div class="col-md-3 col-sm-6">
            <div class="info-box responsive">
                <span class="info-box-icon text-bg-primary">
                    <i class="bi bi-box"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Products</strong></span>
                    <span class="info-box-number"><?= $total_products ?></span>
                </div>
            </div>
        </div>

        <!-- SALES -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-success">
                    <i class="bi bi-cart"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Total Sales</strong></span>
                    <span class="info-box-number"><?= $total_sales ?></span>
                </div>
            </div>
        </div>

        <!-- TOP ITEM -->
         <div class="col-md-3 col-sm-6">
             <div class="info-box">
                <span class="info-box-icon text-bg-success">
                    <i class="bi bi-trophy"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Top Selling Item</strong></span>
                    <span class="info-box-number">
                        <?= $top_item['product_name'] ?? 'N/A' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- LOW ITEM -->
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger">
                    <i class="bi bi-arrow-down"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Lowest Selling Item</strong></span><span class="info-box-number">
                        <?= $low_item['product_name'] ?? 'N/A' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

       <!-- CHARTS -->
        <div class="row">
            <!-- Chart 1 -->
            <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Sales by Department</h5>
                    </div>
                    <div class="card-body chart-box">
                        <canvas id="departmentChart" height="300"></canvas>
                    </div>
                    <div class="card-footer">
                            last updated 11:59 PM
                    </div>
                </div>
            </div>
        <!-- Chart 2 -->
            <div class="col-md-6 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Sales by Semester</h5>
                    </div>
                    <div class="card-body chart-box">
                        <canvas id="semesterChart" height="300"></canvas>
                    </div>
                    <div class="card-footer">
                        last updated 11:59 PM
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </main>
 </body>
</html>

<script>

    /* Department BAR */
new Chart(document.getElementById("departmentChart"),{
    type:"bar",
    data:{
        labels: <?= json_encode($dept_labels) ?>,
        datasets:[{
            label:"Sales",
            data: <?= json_encode($dept_sales) ?>,
             backgroundColor:[
                "#0d6efd",
          ]
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{
                display:false
            }
        },
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});


/* Semester PIE */
new Chart(document.getElementById("semesterChart"),{
    type:"pie",
    data:{
        labels: <?= json_encode($sem_labels) ?>,
        datasets:[{
            data: <?= json_encode($sem_sales) ?>,
            backgroundColor:[
                "#0d6efd",
                "#198754",
                "#ffc107",
                "#dc3545",
                "#6f42c1"
            ]
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{
                display:true,
                position: "top"
            }
        },
    }
});
</script>

<?php include("../includes/footer.php"); ?>


