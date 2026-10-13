<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../includes/connection.php");
include("../auth/check_login.php");
include("../includes/header.php");
include("../auth/middlewar.php");

checkRole(['staff']);




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


/* LOW STOCK ITEMS */
$low_stock_items = $conn->query("
SELECT COUNT(*) AS total
FROM products
WHERE stock <= 5
")->fetch_assoc()['total'];


/* TODAY SALES */
$today_sales = $conn->query("
SELECT COUNT(*) AS total
FROM sales
WHERE DATE(sale_date) = CURDATE()
")->fetch_assoc()['total'];


/* TODAY REVENUE */
$today_revenue = $conn->query("
SELECT IFNULL(SUM(total_price),0) AS total
FROM sales
WHERE DATE(sale_date) = CURDATE()
")->fetch_assoc()['total'];


/* ITEMS SOLD TODAY */
$items_sold_today = $conn->query("
SELECT IFNULL(SUM(quantity),0) AS total
FROM sales
WHERE DATE(sale_date) = CURDATE()
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


/* MONTHLY SALES CHART */

$daily_sql = "
SELECT DATE(sale_date) as sale_day,
SUM(total_price) as total_sales
FROM sales
GROUP BY DATE(sale_date)
ORDER BY sale_day
";

$daily_result = mysqli_query($conn,$daily_sql);

$days = [];
$daily_sales = [];

while($row = mysqli_fetch_assoc($daily_result)){
    $days[] = date("M j", strtotime($row['sale_day']));
    $daily_sales[] = $row['total_sales'];
}




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
TOP BUYERS students
============================= */
$student_labels=[];
$student_sales=[];

$student=$conn->query("
SELECT st.student_name,
SUM(s.total_price) as total
FROM sales s
JOIN students st ON s.student_id=st.student_id
GROUP BY st.student_id
ORDER BY total DESC
LIMIT 5
");

while($row=$student->fetch_assoc()){
$student_labels[]=$row['student_name'];
$student_sales[]=$row['total'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Staff Dashboard</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <h3>Staff Dashboard</h3>
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

        <!-- LOW STOCK -->
        <div class="col-md-3 col-sm-6">
            <div class="info-box responsive">
                <span class="info-box-icon text-bg-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Low Stock Items</strong></span>
                    <span class="info-box-number"><?= $low_stock_items ?></span>
                </div>
            </div>
        </div>

        <!-- TODAY SALES -->
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary">
                    <i class="bi bi-cart"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Today Sales</strong></span>
                    <span class="info-box-number"><?= $today_sales ?></span>
                </div>
            </div>
        </div>

        <!-- TODAY REVENUE -->
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-success">
                    <i class="bi bi-cash-stack"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Today Revenue</strong></span>
                    <span class="info-box-number">$<?= number_format($today_revenue,2) ?></span>
                </div>
            </div>
        </div>

        <!-- ITEMS SOLD -->
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon text-bg-info">
                    <i class="bi bi-box-seam"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><strong>Items Sold Today</strong></span>
                    <span class="info-box-number"><?= $items_sold_today ?></span>
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
                        <h5>Daily Sales Trend</h5>
                    </div>
                    <div class="card-body chart-box">
                        <canvas id="dailySalesChart" height="300"></canvas>
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

/* Monthly Sales LINE */
new Chart(document.getElementById('dailySalesChart'),{
    type:'line',
    data:{
        labels: <?= json_encode($days) ?>,
        datasets:[{
            label:'Daily Sales',
            data: <?= json_encode($daily_sales) ?>,
            borderColor:'#0d6efd',
            backgroundColor:'rgba(13,110,253,0.25)',
            pointBackgroundColor:'#0d6efd',
            pointBorderColor:'#fff',
            pointRadius:6,
            borderWidth:3,
            tension:0.4,
            fill:true
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


</script>

<?php include("../includes/footer.php"); ?>