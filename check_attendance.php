<?php 
include "files/function.php";
if(!isset($_SESSION['adminLogin'][0]) && !isset($_SESSION['adminLogin'][1]) && empty($_SESSION['adminLogin'][0]) && empty($_SESSION['adminLogin'][1])) {
    header('location:index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['date'])) {
        // Sanitize and validate date
        $date = $_POST['date'];

        // Prepare SQL query
        $run = $con->prepare("SELECT e.*, d.attendance_date, d.status 
                              FROM `employee` e 
                              LEFT JOIN `attendance` d ON e.id = d.employee_id 
                              WHERE d.attendance_date = :date
                              ORDER BY e.id DESC");
        $run->bindParam(':date', $date);
        
        if ($run->execute()) {
            $employeeData = $run->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
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

<?php include "files/sidebar.php" ?>
<div class="content-wrap">
    <div class="main"></div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="date" name="date" id="date">
                <button type="submit">Search</button>
            </form>
            <h5 class="card-title">Employee Attendance</h5>
        </div>
        
        <?php if(isset($employeeData) && is_array($employeeData)): ?>
            <div class="table-responsive">
                <table class="table table-stripped text-white table-borderless mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Status</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employeeData as $key => $value): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo ucwords($value['full_name']); ?></td>
                                <td><?php echo ucwords($value['status']); ?></td>
                                <td><?php echo ucwords($value['employee_role']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript libraries -->
<script src="js/lib/jquery.min.js"></script>
<script src="js/lib/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/custom.js"></script>

</body>
</html>
