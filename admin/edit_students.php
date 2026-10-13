<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']); 

if(isset($_GET['student_id']) && !empty($_GET['student_id'])) {
  
  $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $_GET['student_id']);
    $stmt->execute();

     $result = $stmt->get_result();

     if($result->num_rows > 0){
        $students = $result->fetch_assoc();
    } else {
        die("Student not found");
    }
    } else {
    die("Invalid Student ID");
}

if (isset($_POST['update'])) {
    $student_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $shift = mysqli_real_escape_string($conn, $_POST['shift']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $student_id = $_GET['student_id'];

    $update_query = "UPDATE students SET 
                        student_name = '$student_name', 
                        phone = '$phone', 
                        department = '$department',
                        semester = '$semester',
                        shift = '$shift',
                        gender = '$gender'
                    WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $update_query)) {
        $success = "Student updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$student_id'");
        $students = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating Student: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<main class="app-main">
  <div class="container mt-4">
    <h3>Edit Student</h3>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" autocomplete="off">
      <div class="mb-3">
        <label for="student_name" class="form-label">Student Name</label>
        <input type="text" name="student_name" class="form-control" id="student_name" required value="<?php echo $students['student_name'] ?>">
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" id="phone" required value="<?php echo $students['phone'] ?>">
      </div>
      <div class="mb-3">
        <label for="department" class="form-label">Department Name</label>
        <select class="form-select" name="department" id="department" required value="<?php echo $students['department'] ?>">
          <option value="Computer Science">Computer Science</option>
          <option value="Health Science">Health Science</option>
          <option value="Medical Lab">Medical Lab</option>
          <option value="Nursing">Nursing</option>
          <option value="Bussiness Administrartion">Bussiness Administrartion</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="semester" class="form-label">Semester</label>
        <select class="form-select" name="semester" id="semester" required value="<?php echo $students['semester'] ?>">
          <option value="Semester 1">Semester 1 </option>
          <option value="Semester 2">Semester 2</option>
          <option value="Semester 3">Semester 3</option>
          <option value="Semester 4">Semester 4</option>
          <option value="Semester 5">Semester 5</option>
          <option value="Semester 6">Semester 6</option>
          <option value="Semester 7">Semester 7</option>
          <option value="Semester 8">Semester 8</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="shift" class="form-label">Shift</label>
        <select class="form-select" name="shift" id="shift" required value="<?php echo $students['shift'] ?>">
          <option value="Morning">Morning</option>
                  <option value="Afternoon">Afternoon</option>
                  <option value="Evening">Evening</option>
                </select>
              </div>
      <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select" name="gender" id="gender" required value="<?php echo $students['gender'] ?>">
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update Student</button>
      <a href="../admin/manage_student.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</main>

<?php include '../includes/footer.php'; ?>
