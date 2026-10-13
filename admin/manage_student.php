<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin','staff']);


// Hel magaca ardayga la filter-gareynayo
$student_name = isset($_GET['student_name']) ? $_GET['student_name'] : '';

// Query filter
if (!empty($student_name)) {
    $query = "SELECT * FROM students WHERE student_name = '$student_name'";
} else {
    $query = "SELECT * FROM students";
}
$result = mysqli_query($conn, $query);

// Query for select options
$student_options = mysqli_query($conn, "SELECT DISTINCT student_name FROM students");


// Dashboard link based on role
$role = $_SESSION['role'] ?? '';

if ($role === 'admin') {
    $dashboard_link = "../admin/admin_dashboard.php";
} elseif ($role === 'staff') {
    $dashboard_link = "../staff/staff_dashboard.php";
} else {
    $dashboard_link = "../auth/login.php";
}
?>
<!DOCTYPE html>
<html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Students</title>
    </head>
    <body>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">View Students</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="<?= $dashboard_link ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">View Students</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <!-- Filter Form -->
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title">Filter Students</h5>
        </div>
        <div class="card-body">
          <form method="GET" class="row g-2 align-items-end" action="" autocomplete="off">
            <div class="col-md-4 col-lg-3 col-sm-6">
            <label for="student_name">Filter by Name:</label>
            <select name="student_name" class="form-select">
              <option value="">All Students</option>
              <?php while ($row = mysqli_fetch_assoc($student_options)): ?>
                <option value="<?php echo htmlspecialchars($row['student_name']); ?>" 
                  <?php if ($student_name == $row['student_name']) echo 'selected'; ?>>
                  <?php echo htmlspecialchars($row['student_name']); ?>
                </option>
              <?php endwhile; ?>
            </select>
            </div>
            <div class="col-md-2 col-lg-1 col-sm-6 d-flex allign-items-end">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Registered Students Table -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Registered Students</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
          <table class="table mt-3 table-re">
            <thead>
              <tr>
                <th>Student ID</th>
                <th>StdName</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Semester</th>
                <th>Shift</th>
                <th>Gender</th>
                <?php if ($role === 'admin'): ?>
                <th>Action</th> 
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['semester']); ?></td>
                    <td><?php echo htmlspecialchars($row['shift']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    
                  <?php if ($role === 'admin'): ?>
                    <td>
                      <a href="edit_students.php?student_id=<?= $row['student_id']; ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                      <a href="delete_students.php?student_id=<?= $row['student_id']; ?>" class="btn btn-danger" 
                      onclick="return confirm('Are you sure you want to delete this student?')">
                    <i class="bi bi-trash"></i></a>
                    </td>
                  <?php endif; ?>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="11">No students found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include '../includes/footer.php'; ?>


