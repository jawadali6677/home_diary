<?php 
include "files/function.php";

// Check if admin is logged in
if(!isset($_SESSION['adminLogin'][0]) || !isset($_SESSION['adminLogin'][1]) || empty($_SESSION['adminLogin'][0]) || empty($_SESSION['adminLogin'][1])) {
	header('location:index.php');
	exit();
}

// Initialize variables for form submission
$employee_id = isset($_POST['id']) ? $_POST['id'] : '';
$start_date = isset($_POST['from']) ? $_POST['from'] : date('Y-m-01');
$end_date = isset($_POST['to']) ? $_POST['to'] : date('Y-m-d');

$_SESSION['emp_id'] = $employee_id;

// Prepare SQL query to fetch employee data along with absentee counts
$sql = "SELECT e.*, 
               COUNT(CASE WHEN d.status = 'absent' THEN 1 END) AS absent_count
        FROM `employee` e
        LEFT JOIN `attendance` d ON e.id = d.employee_id 
                                  AND d.attendance_date BETWEEN :start_date AND :end_date
        WHERE (:employee_id = '' OR e.id = :employee_id)
        GROUP BY e.id
        ORDER BY e.id DESC";

$run = $con->prepare($sql);
$run->bindParam(':start_date', $start_date);
$run->bindParam(':end_date', $end_date);
$run->bindParam(':employee_id', $employee_id);
$run->execute();
$employeesalaryData = $run->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee</title>
  <link href="css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

<?php include "files/sidebar.php"; ?>
<div class="content-wrap">
  <div class="main"></div>
  <div class="card">
    
    <form class="d-flex mt-3" method="POST" action="">
      <div class="form-group">
        <label for="employee_select">Select Employee:</label>
        <select id="employee_select" name="id" class="form-control mb-4 shadow rounded-0" required>
          <option value="">Select an Employee</option>
          <?php foreach ($employeesalaryData as $employee): ?>
            <option value="<?php echo $employee['id']; ?>" <?php if($_SESSION['emp_id'] == $employee['id']) { ?> selected <?php } ?>><?php echo ucwords($employee['full_name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group ml-2">
        <label for="from">From:</label>
        <input class="form-control me-2" type="date" id="from" name="from" value="<?php echo $start_date; ?>" required>
      </div>
      <div class="form-group ml-2">
        <label for="to">To:</label>
        <input class="form-control me-2" type="date" id="to" name="to" value="<?php echo $end_date; ?>" required>
      </div>

      <div class="mt-4 ml-2">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
        <a href="" class="btn btn-danger ml-2 mt-2">Reset</a>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-stripped text-white table-borderless mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Role</th>
            <th>Salary</th>
            <th>Absent</th>
            <th>Net Salary</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $count = 1;
          
          foreach ($employeesalaryData as $value) {
            $salary = floatval($value['salary']); // Convert salary to float
            $absentCount = intval($value['absent_count']); // Convert absent count to integer
            $netSalary = $salary - ($absentCount * ($salary/30)); // Assuming $50 deduction per absent day

            echo '<tr>
                    <td>'.$count.'</td>
                    <td>'.ucwords($value['full_name']).'</td>
                    <td>'.ucwords($value['employee_role']).'</td>
                    <td>'.ucwords($value['salary']).'</td>
                    <td>'.ucwords($value['absent_count']).'</td>
                    <td>'.number_format($netSalary, 2).'</td>
                  </tr>';
            $count++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Bootstrap and jQuery Scripts -->
<script src="js/lib/jquery.min.js"></script>
<script src="js/lib/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
<script>
$(document).ready(function() {
  // Calculate net salary for each employee
  $('#employee_table_body').find('tr').each(function() {
    var salary = parseFloat($(this).find('td:eq(3)').text()); // Column index of Salary
    var absentCount = parseInt($(this).find('td:eq(4)').text()); // Column index of Absent Count

    if (!isNaN(salary) && !isNaN(absentCount)) {
      var netSalary = salary - (absentCount * 50); // Assuming $50 deduction per absent day
      $(this).find('td:eq(5)').text(netSalary.toFixed(2)); // Column index of Net Salary
    }
  });
});
</script>

</body>
</html>
