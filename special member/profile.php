<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();   
include("../includes/connection.php");
include("../includes/header.php");

 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");   
    exit();
}

 
$user_id = $_SESSION['user_id'];   
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit();
}

mysqli_close($conn);
?>

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Your Profile</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Your Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <!-- User Profile Display -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Profile Information</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" value=<?php echo $user["username"]?> disabled>
            </div>
            <div class="col-md-4 mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullname" value= <?php echo$user["full_name"] ?> disabled>
            </div>
            <div class="col-md-4 mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" value=<?php echo $user["email"] ?> disabled>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php
include "../includes/footer.php";
?>
