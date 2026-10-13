<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);


if(isset($_GET['product_id']) && !empty($_GET['product_id'])) {

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("s", $_GET['product_id']);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $products = $result->fetch_assoc();
    } else {
        die("Product not found");
    }

} else {
    die("Invalid Product ID");
}

if (isset($_POST['update'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);

    $product_id = $_GET['product_id'];

    $update_query = "UPDATE products SET 
                        product_name = '$product_name', 
                        category = '$category', 
                        price = '$price',
                        stock = '$stock'
                    WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $update_query)) {
        $success = "product updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$product_id'");
        $products = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating product: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<main class="app-main">
  <div class="container mt-4">
    <h3>Edit Product</h3>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="product_name" class="form-label">product Name</label>
        <input type="text" name="product_name" class="form-control" id="product_name"  value="<?= $products['product_name'] ?>">
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <input type="text" name="category" class="form-control" id="category"  value="<?= $products['category'] ?>">
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">price</label>
        <input type="number" name="price" class="form-control" id="price"  value="<?= $products['price'] ?>">
      </div>
      <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" id="stock"  value="<?= $products['stock'] ?>">
    </div>
      <button type="submit" name="update" class="btn btn-primary">Update Product</button>
      <a href="../admin/manage_products.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
