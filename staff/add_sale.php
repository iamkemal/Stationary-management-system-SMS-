<?php
session_start();
error_reporting(E_ALL);

ini_set('display_errors', 1);

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['staff']);

$sold_by = $_SESSION['user_id'];

/* ===== GENERATE NEXT SALE ID ===== */
$result = mysqli_query(
    $conn,
    "SELECT sale_id FROM sales 
     ORDER BY CAST(SUBSTRING(sale_id,4) AS UNSIGNED) DESC 
     LIMIT 1"
);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $number = (int)substr($row['sale_id'], 3); // SAL001 → 1
    $nextNumber = $number + 1;
} else {
    $nextNumber = 1;
}

$generatedID = 'SAL' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

/* ===== HANDLE INSERT ===== */
if (isset($_POST["submit"])) {

    $student_id = $_POST["student_id"];
    $product_id = $_POST["product_id"];
    $quantity   = (int)$_POST["quantity"];
    $date       = date("Y-m-d H:i:s");

    // Get product price
    $product_query = mysqli_query($conn, "SELECT price, stock FROM products WHERE product_id='$product_id'");
    $product = mysqli_fetch_assoc($product_query);
    $total_price = $product['price'] * $quantity;

    // Check stock
    if ($quantity > $product['stock']) {
        $error = "Not enough stock. Available: " . $product['stock'];
    } else {
        $stmt = $conn->prepare(
          "INSERT INTO sales (sale_id, student_id, product_id, quantity, total_price, sale_date, sold_by)
          VALUES (?, ?, ?, ?, ?, ?, ?)"
);

         $sale_date = date("Y-m-d H:i:s");
         
          $stmt->bind_param(
      "sssdisi",
      $generatedID,
      $student_id,
      $product_id,
      $quantity,
      $total_price,
      $sale_date,
      $sold_by
    );

        $conn->begin_transaction();

try {
    if ($stmt->execute()) {

         $product = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT stock FROM products WHERE product_id='$product_id'")
        );

        if ($quantity > $product['stock']) {
            throw new Exception("Stock not enoughs");
        }

        $new_stock = $product['stock'] - $quantity;

        $update = $conn->prepare(
            "UPDATE products SET stock=? WHERE product_id=?"
        );
        $update->bind_param("is", $new_stock, $product_id);
        $update->execute();

        $conn->commit();
        $success = "Sale added successfully";
    }
} catch (Exception $e) {
    $conn->rollback();
    $error = $e->getMessage();
}
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Sale</title>
</head>
<body>
 <main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Add Sale</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="../staff/staff_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active"> Add Sale</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Add New Sale</h5>
        </div>
        <div class="card-body">

          <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
          <?php if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>'; ?>
         
          <form action="" method="POST" autocomplete="off">
            <!-- Row 1 -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="id" class="form-label">Sale ID</label>
                <input type="text" name="id" class="form-control" value="<?php echo $generatedID; ?>" readonly> 
              </div>
              <div class="col-md-4">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    <?php
                    $students = mysqli_query($conn, "SELECT student_id, student_name FROM students ORDER BY student_name ASC");
                    while($row = mysqli_fetch_assoc($students)) {
                        echo "<option value='".$row['student_id']."'>".$row['student_name']."</option>";
                    }
                    ?>
                </select>
              </div>
              <div class="col-md-4">
                <label for="product_id" class="form-label">Product</label>
                <select name="product_id" class="form-control" required>
                    <option value="">Select Product</option>
                    <?php
                    $products = mysqli_query($conn, "SELECT product_id, product_name FROM products ORDER BY product_name ASC");
                    while($row = mysqli_fetch_assoc($products)) {
                        echo "<option value='".$row['product_id']."'>".$row['product_name']."</option>";
                    }
                    ?>
                </select>
              </div>
            </div>

            <!-- Row 2 -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" min="1" placeholder="Enter quantity" required>
              </div>
              <div class="col-md-6">
                <label for="total" class="form-label">Total Price </label>
                <input type="text" class="form-control" name="total" id="total" placeholder="Total Price" readonly>
              </div>
            </div>

            <button class="btn btn-primary col-md-12" name="submit" type="submit"><b>Save Sale</b></button>
          </form>
        </div>
      </div>
    </div>   
  </main>

  <script>
    // Auto-calculate total price
    const productSelect = document.querySelector('select[name="product_id"]');
    const quantityInput = document.querySelector('input[name="quantity"]');
    const totalInput = document.querySelector('input[name="total"]');

    let productPrices = {};
    <?php
    $product_query = mysqli_query($conn, "SELECT product_id, price FROM products");
    while($row = mysqli_fetch_assoc($product_query)) {
        echo "productPrices['".$row['product_id']."'] = ".$row['price'].";\n";
    }
    ?>

    function calculateTotal() {
        const price = productPrices[productSelect.value] || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        totalInput.value = price * quantity;
    }

    productSelect.addEventListener('change', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
  </script>

</body>
</html>

<?php 
include("../includes/footer.php")
?>