<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

if(isset($_GET['success'])){
   $success = "New product added successfully!";
} elseif(isset($_GET['error'])){
    $error = "Error: " . mysqli_error($conn);
}


/* GENERATE PRODUCT ID */

$query = "SELECT product_id FROM products ORDER BY product_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    preg_match('/\d+/', $row['product_id'], $matches);

    $lastID = (int)$matches[0] + 1;

} else {

    $lastID = 1;

}

$generatedID = 'PRDLIB' . str_pad($lastID, 3, '0', STR_PAD_LEFT);



/* INSERT PRODUCT */

if (isset($_POST["submit"])) {

$product_id = $generatedID;
$product_name = $_POST["product_name"];
$price = $_POST["price"];
$stock = $_POST["stock"];
$category = $_POST["category"];

$stmt = $conn->prepare("INSERT INTO products (product_id, product_name, price, stock, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $product_id, $product_name, $price, $stock, $category);

    if ($stmt->execute()) {
        header("Location: add_products.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add Product</title>
</head>
<body>
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Add Product</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">
                                <a href="admin_dashboard.php">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">Add Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>



<div class="app-content">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Add New Product</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
                <?php if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>'; ?>
                <form action="" method="POST">
   <!-- ROW 1 -->
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">Product ID</label>
        <input type="text" name="product_id" class="form-control" value="<?php echo $generatedID; ?>"readonly>
    </div>
    <div class="col-md-4">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" class="form-control" placeholder="Enter product name" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" placeholder="Enter category" required>
    </div>
</div>
  <!-- ROW 2 -->
   <div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label">Price ($)</label>
        <input type="number" step="0.1" name="price" class="form-control" placeholder="Enter price" required>
    </div>



<div class="col-md-6">
    <label class="form-label">Stock</label>
    <input type="number" name="stock" class="form-control" placeholder="Enter stock quantity" required>
</div>
</div>



<button class="btn btn-primary col-md-12" name="submit" type="submit">

Add Product

</button>

</form>


</div>
</div>
</div>
</div>
</main>

</body>
</html>



<?php
include("../includes/footer.php")
?>