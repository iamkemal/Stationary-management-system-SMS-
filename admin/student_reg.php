<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../includes/connection.php");
include("../includes/header.php");
include("../auth/check_login.php");
include("../auth/middlewar.php");

checkRole(['admin']);

if(isset($_GET['success'])){
    $success = "New student added successfully!";
} else if(isset($_GET['error'])){
    $error = "Error: " . mysqli_error($conn);
}

// Query to get the last student_id
$query = "SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Extract numeric part and increment it
    preg_match('/\d+/', $row['student_id'], $matches);
    $lastID = (int)$matches[0] + 1;  // Increment the numeric part
} else {
    $lastID = 1;
}

// Format new student_id
$generatedID = 'STDLIB' . str_pad($lastID, 3, '0', STR_PAD_LEFT);
 
if (isset($_POST["submit"])) {
    $student_id = $generatedID;
    $student_name = $_POST["student_name"];
    $phone = $_POST["phone"];
    $department = $_POST["department"];
    $semester = $_POST["semester"];
    $shift = $_POST["shift"];
    $gender = $_POST["gender"];
    

    $stmt = $conn->prepare("INSERT INTO students (student_id, student_name, phone, department, semester, shift, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $student_id, $student_name, $phone, $department, $semester, $shift, $gender);

    if ($stmt->execute()) {
        header("Location: student_reg.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if(isset($_POST['import_csv'])){

    if(isset($_FILES['csv_file']['tmp_name'])){

        $file = fopen($_FILES['csv_file']['tmp_name'], "r");

        fgetcsv($file); 

        $imported = 0;
        $duplicates = []; 

        while(($row = fgetcsv($file)) !== FALSE){

            $lastID++;
            $student_id = 'STDLIB' . str_pad($lastID, 3, '0', STR_PAD_LEFT);

            $student_name = mysqli_real_escape_string($conn, $row[0]);
            $phone        = mysqli_real_escape_string($conn, $row[1]);
            $department   = mysqli_real_escape_string($conn, $row[2]);
            $semester     = mysqli_real_escape_string($conn, $row[3]);
            $shift        = mysqli_real_escape_string($conn, $row[4]);
            $gender       = mysqli_real_escape_string($conn, $row[5]);

            // check duplicate
            $check = mysqli_query($conn,"SELECT * FROM students WHERE student_id='$student_id'");

            if(mysqli_num_rows($check) > 0){
                $duplicates[] = "Student ID: $student_id already exists";
                continue; 
            }

            mysqli_query($conn,"INSERT INTO students 
            (student_id, student_name, phone, department, semester, shift, gender)
            VALUES 
            ('$student_id','$student_name','$phone','$department','$semester','$shift','$gender')");

            $imported++;
        }

        fclose($file);

        // success message
        $success = "$imported students imported successfully!";

        $duplicates = array_slice($duplicates, 0, 10);

        // duplicate message
        if(!empty($duplicates)){
            $error = "<strong>Duplicate IDs:</strong><br>" . implode("<br>", $duplicates);
        }

    } else {
        $error = "Please upload a CSV file!";
    }
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Registration</title>
  <!-- Make sure to include Bootstrap CSS if not already included -->
</head>
<body>

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Student Registration</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="../admin/admin_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Student Registration</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card mb-4"> 
        <div class="card-header">
          <h5 class="card-title">Add New Students</h5>
          <div class="col-md-12 text-end">
            <form method="POST" enctype="multipart/form-data" style="display:inline-block;">
              <!-- Hidden file input -->
                <input type="file" name="csv_file" id="csvFile" accept=".csv" style="display:none;" required>

              <!-- Visible button -->
                 <button type="button" class="btn btn-success" onclick="document.getElementById('csvFile').click()">
                   Import Students
                  </button>

              <!-- Optional: Submit button after file selected -->
          <button type="submit" name="import_csv" id="submitBtn" class="btn btn-primary" style="display:none;">
           Submit
          </button>
      </form>
          </div>
        </div>
        <div class="card-body"> 

          <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
          <?php if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>'; ?>

          <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- Row 1 -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="student_id" class="form-label">Student ID</label> 
                <input type="text" name="student_id" class="form-control" value="<?php echo $generatedID; ?>" readonly>
              </div>
              <div class="col-md-4">
                <label for="student_name" class="form-label">Student Name</label>
                <input type="text" name="student_name" class="form-control" id="student_name" placeholder="enter student name" required>
              </div>
              <div class="col-md-4">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" id="phone" placeholder="enter phone number" required>
              </div>
            </div>

            <!-- Row 2 -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="department" class="form-label">Department Name</label>
                <select class="form-select" name="department" id="department" required>
                  <option value=""> Select Department </option>
                  <option value="Computer Science">Computer Science</option>
                  <option value="Health Science">Health Science</option>
                  <option value="Medical Lab">Medical Lab</option>
                  <option value="Nursing">Nursing</option>
                  <option value="Bussiness Administrartion">Bussiness Administrartion</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select" name="semester" id="semester" required>
                  <option value=""> Select Semester </option>
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
              <div class="col-md-4">
                <label for="shift" class="form-label">Shift</label>
                <select class="form-select" name="shift" id="shift" required>
                  <option value=""> Select Shift </option>
                  <option value="Morning">Morning</option>
                  <option value="Afternoon">Afternoon</option>
                  <option value="Evening">Evening</option>
                </select>
              </div>
            </div>

            <!-- Row 3 -->
            <div class="row mb-3">
            
              <div class="col-md-12">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" name="gender" id="gender" required>
                  <option value=""> Select Gender </option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
            <button class="btn btn-primary col-md-12" name="submit" type="submit">Submit</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

</body>
</html>

<script>
  // Show submit button after a file is chosen
  const fileInput = document.getElementById('csvFile');
  const submitBtn = document.getElementById('submitBtn');

  fileInput.addEventListener('change', function() {
    if (fileInput.files.length > 0) {
      submitBtn.style.display = 'inline-block';
    }
  });
</script>


<?php 

include("../includes/footer.php")
?>