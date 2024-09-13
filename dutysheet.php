<?php

include "files/function.php"; // Include necessary functions

// Redirect to login if admin session not set
if (!isset($_SESSION['adminLogin'][0]) && !isset($_SESSION['adminLogin'][1]) && empty($_SESSION['adminLogin'][0]) && empty($_SESSION['adminLogin'][1])) {
  header('location:index.php');
  exit();
}


$search_term = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_term'])) {
  $search_term = $_POST['search_term'];
}

// Database connection assumed to be $con
$sql = "SELECT e.*, d.start_time, d.end_time 
        FROM `employee` e 
        LEFT JOIN `duty_sheet` d ON e.id = d.employee_id 
        WHERE d.employee_id IS NULL";

// Apply search term if provided
if (!empty($search_term)) {
    $sql .= " AND e.full_name LIKE :search_term";
}

// Order by employee ID
$sql .= " ORDER BY e.id DESC";

// Prepare and execute the query
$run = $con->prepare($sql);

// Bind the search term if it is not empty
if (!empty($search_term)) {
    $run->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
}

if ($run->execute()) {
    $employeeData = $run->fetchAll(PDO::FETCH_ASSOC);
}
// Fetch employee data along with duty sheet times
// Default query: get all records
$sql = "SELECT d.*, e.full_name, e.employee_role, e.id
        FROM `duty_sheet` d 
        LEFT JOIN `employee` e ON e.id = d.employee_id";

// Apply search term if provided
if (!empty($search_term)) {
    $sql .= " WHERE e.full_name LIKE :search_term";
}

// Order by duty ID
$sql .= " ORDER BY d.duty_id ASC";

// Prepare and execute the query
$run = $con->prepare($sql);

// Bind the search term if it is not empty
if (!empty($search_term)) {
    $run->bindValue(':search_term', "%$search_term%", PDO::PARAM_STR);
}

if ($run->execute()) {
    $dutysheetData = $run->fetchAll(PDO::FETCH_ASSOC);
}

// print_r($dutysheetData); exit;
// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_duty'])) {

    
//   $employee_id = $_POST['id'];
//   $mon_duty_start = $_POST['mon_duty_start'];
//   $mon_duty_end = $_POST['mon_duty_end'];
//   $tue_duty_start = $_POST['tue_duty_start'];
//   $tue_duty_end = $_POST['tue_duty_end'];
//   $wed_duty_start = $_POST['wed_duty_start'];
//   $wed_duty_end = $_POST['wed_duty_end'];
//   $thur_duty_start = $_POST['thur_duty_start'];
//   $thur_duty_end = $_POST['thur_duty_end'];
//   $fri_duty_start = $_POST['fri_duty_start'];
//   $fri_duty_end = $_POST['fri_duty_end'];
//   $sat_duty_start = $_POST['sat_duty_start'];
//   $sat_duty_end = $_POST['sat_duty_end'];
//   $sun_duty_start = $_POST['sun_duty_start'];
//   $sun_duty_end = $_POST['sun_duty_end'];
  
//   // print_r($_POST); exit;
//   // Insert duty sheet data into database
//   $stmt = $con->prepare("INSERT INTO `duty_sheet`(`mon_start_time`, `mon_end_time`, `tue_start_time`, `tue_end_time`, `wed_start_time`, `wed_end_time`, `thur_start_time`, `thur_end_time`, `fri_start_time`, `fri_end_time`, `sat_start_time`, `sat_end_time`, `sun_start_time`, `sun_end_time`, `employee_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

//   if ($stmt->execute([$mon_duty_start, $mon_duty_end, $tue_duty_start, $tue_duty_end, $wed_duty_start, $wed_duty_end, $thur_duty_start, $thur_duty_end, $fri_duty_start, $fri_duty_end, $sat_duty_start, $sat_duty_end, $sun_duty_start, $sun_duty_end, $employee_id])) {
//     header('Location: ' . $_SERVER['PHP_SELF']);
//     echo "Dutysheet added successfully";
//   } else {
//     echo "Failed to add dutysheet";
//   }

$employee_id = $_POST['id'];
$duty_sheet = $_POST['duty_sheet'];

// Prepare the SQL statement
$stmt = $con->prepare("INSERT INTO `duty_sheet`(`day`, `start_time`, `end_time`, `employee_id`) VALUES (?,?,?,?)");

foreach ($duty_sheet as $duty) {

    $day = isset($duty['day']) ? $duty['day'] : null;
    $start_time = isset($duty['start_time']) ? $duty['start_time'] : null;
    $end_time = isset($duty['end_time']) ? $duty['end_time'] : null;

    // Only insert if both start_time and end_time are provided
    if (!empty($day) && !empty($start_time) && !empty($end_time)) {
        
        if (!$stmt->execute([$day, $start_time, $end_time, $employee_id])) {
            echo "Failed to add dutysheet for $day";
        }
    }
}

header('Location: ' . $_SERVER['PHP_SELF']);
echo "Dutysheet added successfully";
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

    <?php include "files/sidebar.php"; ?>

    <div class="content-wrap">
        <div class="main">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Duty Sheet</h5>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dutysheetModal">
                        Add New Duty Sheet
                    </button>
                </div>
                <?php if (isset($dutysheetData) && is_array($dutysheetData)): ?>
                <form class="d-flex mt-3" action="" method="post">
                    <input class="form-control me-2" type="search" placeholder="Search" name="search_term"
                        aria-label="Search" value="<?php echo $search_term; ?>">
                    <button class="ml-2 mt-1 btn btn-success" type="submit">Search</button>
                    <a class="ml-2 mt-1 btn btn-danger" href="">Reset</a>
                </form>
                <div class="table-responsive">
                    <table class="table table-stripped text-white table-borderless mt-3">
                        <thead class="text-dark">
                            <tr class="text-dark">
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="allEmployee">
                            <?php
                $count = 1;
                 foreach ($dutysheetData as $key => $value): ?>
                            <?php 
                      if(isset($value['start_time'])){
                    ?>

                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo ucwords($value['full_name']); ?></td>
                                <td><?php echo ucwords($value['day']); ?></td>
                                <td><?php echo convertTo12Hour($value['start_time']); ?></td>
                                <td><?php echo convertTo12Hour($value['end_time']); ?></td>
                                <td><?php echo ucwords($value['employee_role']); ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button class="btn btn-primary btn-sm editBtnDutysheet"
                                            data-userid="<?php echo $value['employee_id']; ?>"
                                            data-id="<?php echo $value['duty_id']; ?>"
                                            data-start="<?php echo $value['start_time']; ?>"
                                            data-end="<?php echo $value['end_time']; ?>"
                                            data-day="<?php echo $value['day']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger ml-2 btn-sm deleteBtnDutysheet"
                                            data-id="<?php echo $value['duty_id']; ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php }  ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Duty Sheet Modal -->
    <div class="modal fade" id="dutysheetModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Duty Sheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body " style="margin-top: 200px;">
                    <form action="" method="post" class="submitForm">
                        <div class="form-group">
                            <label for="employee_select">Select Employee:</label>
                            <select id="employee_select" name="id" class="form-control mb-4 shadow rounded-0" required>
                                <option value="">Select an Employee</option>
                                <?php foreach ($employeeData as $employee): ?>
                                <option value="<?php echo $employee['id']; ?>">
                                    <?php echo ucwords($employee['full_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="employee_role">Employee Role:</label>
                            <input type="text" id="employee_role" name="employee_role"
                                class="form-control mb-4 shadow rounded-0" required>
                        </div>
                        <input type="hidden" name="add_duty" value="true">
                        <table class="table">
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[0][day]" value="Monday"> Monday</td>
                                <td><input type="time" name="duty_sheet[0][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[0][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[1][day]" value="Tuesday"> Tuesday</td>
                                <td><input type="time" name="duty_sheet[1][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[1][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[2][day]" value="Wednesday"> Wednesday</td>
                                <td><input type="time" name="duty_sheet[2][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[2][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[3][day]" value="Thursday"> Thursday</td>
                                <td><input type="time" name="duty_sheet[3][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[3][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[4][day]" value="Friday"> Friday</td>
                                <td><input type="time" name="duty_sheet[4][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[4][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[5][day]" value="Satureday"> Satureday</td>
                                <td><input type="time" name="duty_sheet[5][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[5][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="duty_sheet[6][day]" value="Sunday"> Sunday</td>
                                <td><input type="time" name="duty_sheet[6][start_time]" class="form-control mb-4 shadow"></td>
                                <td><input type="time" name="duty_sheet[6][end_time]" class="form-control mb-4 shadow"></td>
                            </tr>
                            <!-- Repeat similar rows for other days -->
                        </table>
                        <button type="submit" class="btn btn-primary">Save Dutysheet</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- add attendance -->
    <div class="modal fade" id="editDutyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Duty</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form action="files/dutysheet.php" method="post" class="submitUpdateForm">
                            <table class="table" id="update_data">
                               
                                <!-- Repeat similar rows for other days -->
                            </table>
                            <input type="text" hidden name="update_duty" value="true" id="update_duty">
                            <input type="text" hidden name="user_id" value="" id="user_id">
                            <input type="text" hidden name="id" value="" id="id">
                            <button type="submit" class="btn btn-primary">Save Dutysheet</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end add attendance -->

    <!-- JavaScript Dependencies -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        // Handle delete button click
        $('.editBtnDutysheet').click(function() {
            var dutySheetId = $(this).data('id');
            var userId = $(this).data('userid');
            
            var start_time = $(this).data('start');
            var end_time = $(this).data('end');
            var day = $(this).data('day');
            $.ajax({
                url: '<?php echo BASEURL; ?>' + '/files/dutysheet.php',
                type: "POST",
                data : {user_id: userId, editDuty:true},
                success: function(response){
                    var response=$.parseJSON(response);
                    console.log('res' , response);
                    $("#update_data").html(response.data);
                    $("#id").val(dutySheetId)
                    $("#user_id").val(userId)
                    $("#editDutyModal").modal('show');
                    
                },
                error : function(err){
                    console.log(err);
                    
                }
            });
           
            $("#editDutyModal").modal('show');

        });

        $(".deleteBtnDutysheet").click(function(){
            var duty_id = $(this).data('id');

            Swal.fire({
                title: "Do you want to save the changes?",
                showCancelButton: true,
                confirmButtonText: "Save",
                cancelButtonText: "Cancel"
            }).then((result) => {

                if(result.isConfirmed){
                    $.ajax({
                        url: "<?php echo dirname($_SERVER['SCRIPT_NAME']) ?>" + '/files/delete_dutysheet.php', // Replace with your PHP file handling deletion
                        type: 'POST',
                        data: {
                            dutySheetId: duty_id
                        },
                        success: function(response) {

                            Swal.fire('Success' , 'Duty Deleted Successfully' , 'success');
                            setTimeout(() => {  
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr, status, error) {
                            alert('Error deleting duty sheet. Please try again.');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

        // Handle confirmed delete button click
        $('#confirmDeleteBtn').click(function() {
            var dutySheetId = $(this).data('dutysheet-id');

            // AJAX call to delete duty sheet
            $.ajax({
                url: 'files/delete_dutysheet.php', // Replace with your PHP file handling deletion
                type: 'POST',
                data: {
                    dutySheetId: dutySheetId
                },
                success: function(response) {
                    // Refresh the page or update the table after successful deletion
                    location.reload(); // Example: Reload the page
                },
                error: function(xhr, status, error) {
                    alert('Error deleting duty sheet. Please try again.');
                    console.error(xhr.responseText);
                }
            });
        });

        $('#employee_select').on('change', function(e) {
            var id = $(this).val();
            console.log('id', id);

            $.ajax({
                url: window.location.origin + '/home_diary/files/employee.php?emp_id=' + id,
                dataType: 'json',
                method: 'GET',
                success: function(res) {
                    console.log('res', res);
                    $('#employee_role').val(res.employee.employee_role);

                },
                error: function(error) {
                    console.log(error);

                }
            })

        })
    });
    </script>

</body>

</html>