<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);


if(isset($_GET['user_id']) && !empty($_GET['user_id'])) {

  $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $_GET['user_id']);
  $stmt->execute();

  $result = $stmt->get_result();
  if($result->num_rows > 0){
    $user = $result->fetch_assoc();
  } else {
    die("User not found");
  }
} else {
  die("Invalid User ID");
}

 
if (isset($_POST['update'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username =  mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $user_id = $_GET['user_id'];

    $update_query = "UPDATE users SET 
                        full_name = '$full_name', 
                        username = '$username', 
                        email = '$email' 
                     WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        $success = "User updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
        $user = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating user: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit User</title>
    </head>
    <body>
<main class="app-main">
  <div class="container mt-4">
    <h3>Edit User</h3>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" id="fullname" required value=<?php echo $user['full_name'] ?>>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" required value=<?php echo $user['username'] ?>>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" required value=<?php echo $user['email'] ?>>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update User</button>
      <a href="../admin/manage_user.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</main>
    </body>
</html>

<?php include '../includes/footer.php'; ?>
