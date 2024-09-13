<?php 
 // Start session if not already started
include "files/function.php";

// Check if admin is not logged in, redirect to index.php
if (!isset($_SESSION['adminLogin'][0]) && !isset($_SESSION['adminLogin'][1]) && empty($_SESSION['adminLogin'][0]) && empty($_SESSION['adminLogin'][1])) {
    header('location:index.php');
    exit();
}

$selectedDate = date('Y-m-d');
// print_r($_SERVER['REQUEST_METHOD']); exit;
if(isset($_POST['date'])){
    $selectedDate = $_POST['date'];
}
$currentDay = strtolower(date('l', strtotime($selectedDate)));  // 'mon', 'tue', 'wed', etc.

$startTimeField = 'start_time';
// Assuming $con is your PDO connection object
$run = $con->prepare("SELECT e.id AS employee_id, 
                        a.id AS attendance_id, 
                        e.full_name, 
                        e.employee_role, 
                        e.gender, 
                        e.address, 
                        e.phone_no, 
                        e.salary, 
                        d.start_time, 
                        d.end_time,
                        d.day, 
                        a.attendance_date, 
                        a.check_in_at, 
                        a.check_out_at, 
                        a.status
                     FROM `employee` e 
                     LEFT JOIN `duty_sheet` d ON e.id = d.employee_id
                     LEFT JOIN `attendance` a ON e.id = a.employee_id 
                     AND a.attendance_date = :selectedDate
                     WHERE d.day = :currentDay
                     ORDER BY e.id DESC");

$run->bindParam(':selectedDate', $selectedDate, PDO::PARAM_STR);
$run->bindParam(':currentDay', $currentDay, PDO::PARAM_STR);

if ($run->execute()) {
    $employeeData = $run->fetchAll(PDO::FETCH_ASSOC);
}
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//   // Sanitize and validate date
//   $date = $_POST['date']; // Assuming 'date' is the name of your date input
  
//   // Check if any employees were selected
//   if (!empty($_POST['attend_checked'])) {
//       // Prepare to mark selected employees as present
//       $presentQuery = $con->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (:employee_id, :date, 'present')");
//       $presentQuery->bindParam(':date', $date);

//       // Loop through each checkbox value (employee IDs)
//       foreach ($_POST['attend_checked'] as $employee_id) {
//           $presentQuery->bindParam(':employee_id', $employee_id);
//           $presentQuery->execute();
//       }
//       echo "Attendance saved successfully.";
//   }

//   // Prepare to mark all non-selected employees as absent
//   $absentQuery = $con->prepare("SELECT id FROM employee WHERE id NOT IN (" . implode(',', $_POST['attend_checked']) . ")");
//   $absentQuery->execute();
//   $absentEmployees = $absentQuery->fetchAll(PDO::FETCH_COLUMN);

//   if (!empty($absentEmployees)) {
//       // Prepare to mark non-selected employees as absent
//       $absentInsertQuery = $con->prepare("INSERT INTO attendance (employee_id, attendance_date, status) VALUES (:employee_id, :date, 'absent')");
//       $absentInsertQuery->bindParam(':date', $date);

//       foreach ($absentEmployees as $employee_id) {
//           $absentInsertQuery->bindParam(':employee_id', $employee_id);
//           $absentInsertQuery->execute();
//       }
//       echo "Absent status updated successfully.";
//   }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- theme meta -->
    <meta name="theme-name" content="focus" />
    <title>Employee</title>
    <!-- ================= Favicon ================== -->
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    .custom-checkbox-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
        /* Adjust this height as needed */
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-checkbox {
        position: absolute;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
        opacity: 0;
        /* Hide the default checkbox */
        z-index: 2;
    }

    .custom-checkbox+label {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #007bff;
        color: #fff;
        border: 1px solid #007bff;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        z-index: 1;
        border-radius: 5px;
    }

    .custom-checkbox:checked+label+.hidden-fields {
        display: block;
        /* margin-top: 20px; */
    }

    .custom-checkbox:checked+label {
        background-color: #28a745;
        border-color: #28a745;
    }

    .fa-check {
        font-size: 30px;
    }
    </style>
</head>

<body>

    <?php include "files/sidebar.php"; ?>
    <div class="content-wrap">
        <div class="main"></div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Attendance</h5>
            </div>
            <?php if(isset($employeeData) && is_array($employeeData)) { ?>
            <div class="table-responsive">
                <!-- <form method="post" action="">
                <label>Date: <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" onchange="showDay()"></label>
                </form> -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="">Date</label>
                                <input type="date" name="date" id="date" class="form-control" value="<?php echo $selectedDate; ?>">
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-primary mt-2">Search</button>
                                <a href="" class="btn btn-danger ml-2 mt-2">Reset</a>
                            </div>
                        </div>
                    </form>
                    <h5 class="card-title mt-3">Employee Attendance</h5>
                <h6 id="dayDisplay"></h6>
                <table class="table table-stripped text-white table-borderless mt-3">
                    <thead class="text-white">
                        <tr>
                            <!-- <th>Checked</th> -->
                            <th>Dutysheet ID</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Total Hours</th>
                            <th>Check In Time</th>
                            <th>Check Out Time</th>
                            <th>Late</th>
                            <th>Overtime</th>
                            <th>Working Hours</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count = 1;
                            foreach ($employeeData as $key => $value) {
                                // print_r($value); exit;
                                $check_in_btn = '';
                                $check_out_btn = '';
                                $absent = '';
                                $check_in_time = '';
                                $check_out_time = '';
                                $check_icon = '';
                                $close_icon = '';
                                $working_hours = 0;
                                $late_hours = 0;
                                $overtime = 0;
                                
                                $duty_start = $value["start_time"];
                                // print_r(strtotime(date('H:i:s',strtotime($value['check_in_at'])))); 
                                $duty_end = $value["end_time"];

                                $duty_hours = number_format(abs((strtotime($duty_start) - strtotime($duty_end))/3600) , 0);

                                if($value['attendance_date'] == $selectedDate){  

                                    if($value['check_out_at'] == null){

                                        if($value['status'] == 'absent'){

                                            $close_icon = '<i class="fa fa-close text-danger" style="font-size:40px;"></i>';

                                        }else{
                                            $check_in_time = '<span class="p-1 bg bg-success rounded">'.$value['check_in_at'].'</span>';
                                            $check_out_btn = '<button class="btn btn-danger checkOutBtn" data-id="'.$value['employee_id'].'" data-attendanceId="' . $value['attendance_id'] . '">Check Out</button>';
                                        }
                                        
                                    }else{
                                        $check_in_time = '<span class="p-1 bg bg-success rounded">'.$value['check_in_at'].'</span>';
                                        $check_out_time = '<span class="p-1 bg bg-danger rounded">'.$value['check_out_at'].'</span>';
                                        $late_hours = abs((strtotime($duty_start) - strtotime(date('H:i:s',strtotime($value['check_in_at']))))/3600);
                                        $working_hours = abs((strtotime($value['check_out_at']) - strtotime($value['check_in_at']))/3600);
                                        $overtime = $working_hours -  $duty_hours ;

                                        if($value['status'] == 'present'){

                                            $check_icon = '<i class="fa fa-check text-success" style="font-size:40px;"></i>';
                                        }else{

                                            $close_icon = '<i class="fa fa-close text-danger" style="font-size:40px;"></i>';
                                        }
                                    }
                                }else{
                                    $absent = '<button class="btn btn-danger ml-3 absent" data-id="'.$value['employee_id'].'">Absent</button>';
                                    $check_in_btn = '<button class="btn btn-primary ml-3 checkInBtn" data-id="'.$value['employee_id'].'">Check In</button>';
                                }

                                
                                echo '<tr>'.
                                        // <td>
                                        //     <input type="checkbox" name="attend_checked[]" value="' . $value['id'] . '">
                                        // </td>
                                        '<td>' . $count . '</td>
                                        <td>' . ucwords($value['full_name']) . '</td>
                                        <td>' . ucwords($value['employee_role']) . '</td>
                                        <td>' . $duty_hours . '</td>
                                        <td>'. $check_in_time .'</td>
                                        <td>'. $check_out_time .'</td>
                                        <td>'. number_format($late_hours, 0)." Hours" .'</td>
                                        <td>'. number_format($overtime , 0)." Hours" .'</td>
                                        <td>'. number_format($working_hours , 0) .'</td>
                                        <td>';
                                            echo $absent;
                                            echo $check_out_btn;
                                            echo $check_icon;
                                            echo $close_icon;
                                            echo $check_in_btn;
                                            
                                        echo '</td>'.
                                    '</tr>';
                                $count++;
                            }
                            ?>
                    </tbody>
                </table>
                <br>

                <!-- <button type="submit" class="btn btn-primary align-center">Save Attendance</button>
                </form> -->
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- add attendance -->
    <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form action="files/attendance.php" method="post" class="submitUpdateForm">
                            <div class="form-group custom-checkbox-wrapper">
                                <input type="checkbox" id="checkIn" name="checkIn" class="custom-checkbox" required>
                                <label for="checkIn">Check IN<i class="fa fa-check" style="display: none;"></i></label>
                            </div>
                            <div class="form-group hidden-fields">
                                <input type="hidden" id="employee_id" name="employee_id"
                                    class="form-control mb-4 shadow rounded-0 employee_id">
                                <b>Date</b>
                                <input type="date" id="attendance_date" name="attendance_date"
                                    class="form-control mb-4 shadow rounded-0" required>
                                <b>Check In Time</b>
                                <input type="datetime-local" id="check_in_time" name="check_in_time"
                                    class="form-control mb-4 shadow rounded-0" required>
                                <div class="d-flex">
                                    <input type="radio" id="present" checked class="mr-2" name="status" value="present">
                                    <label for="present" class="mr-3 mt-2">Present</label><br>
                                    <!-- <input type="radio" id="absent" class="mr-2" name="status" value="absent">
                                    <label for="absent" class="mt-2">Absent</label><br> -->
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            <div class="alertError"></div>
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

        <!-- update attendance -->
        <div class="modal fade" id="updateAttendanceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Attendance</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form action="files/attendance.php" method="post" class="submitUpdateForm">
                            <div class="form-group custom-checkbox-wrapper">
                                <input type="checkbox" id="checkOut" name="checkOut" class="custom-checkbox" required>
                                <label for="checkOut">Check Out<i class="fa fa-check" style="display: none;"></i></label>
                            </div>
                            <div class="form-group hidden-fields">
                                <input type="hidden" id="employee_id" name="employee_id"
                                    class="form-control mb-4 shadow rounded-0 employee_id">
                                <input type="hidden" id="attendance_Id" name="attendance_Id"
                                    class="form-control attendance_Id">
                                <!-- <b>Date</b>
                                <input type="date" id="attendance_date" name="attendance_date"
                                    class="form-control mb-4 shadow rounded-0" required> -->
                                <b>Check Out Time</b>
                                <input type="datetime-local" id="check_out_time" name="check_out_time"
                                    class="form-control mb-4 shadow rounded-0" required>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            <div class="alertError"></div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end update attendance -->

    <!-- Include your modals and other JavaScript at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="js/lib/jquery.min.js"></script>
    <!-- <script src="js/custom.js"></script> -->
    <!-- Bootstrap -->
    <script src="js/lib/bootstrap.min.js"></script>
    <!-- Sidebar script -->
    <script src="js/lib/menubar/sidebar.js"></script>

    <!-- JavaScript for showing day -->
    <script>
    function showDay() {
        let selectedDate = new Date(document.getElementById('date').value);
        if (!isNaN(selectedDate.getTime())) {
            let days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            let dayName = days[selectedDate.getDay()];
            document.getElementById('dayDisplay').textContent = 'Selected day is: ' + dayName;
        } else {
            document.getElementById('dayDisplay').textContent = '';
        }
    }

    // Show today's day initially
    $(document).ready(function() {

        // showDay();
        $('.checkInBtn , .checkOutBtn').on('click', function(e) {

            var id = $(this).data("id");
            $('.employee_id').val(id)

            if($(this).hasClass('checkOutBtn')){

                var attendanceId = $(this).data("attendanceid");
                // console.log($(this).data());
                $('#updateAttendanceModal').modal('show');
                $('#attendance_Id').val(attendanceId);
            }else{
                $('#addAttendanceModal').modal('show');
            }
        })
    })

    $('.custom-checkbox').on('click', function() {

        $('.fa-check').toggle();
    });

    $(".absent").on("click" , function(){

        var emp_id = $(this).data('id');
        console.log(emp_id);
        var url = "<?php echo dirname($_SERVER['SCRIPT_NAME']) ?>" + "/files/attendance.php";
        var date = "<?php echo $selectedDate; ?>"
        console.log(url);
        
        
        Swal.fire({
            title: "Do you want to save the changes?",
            showCancelButton: true,
            confirmButtonText: "Save",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {emp_id:emp_id, absent:true , date:date},
        
                    success: function(response) {
                        console.log('response', response);
        
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Record has been saved",
                            showConfirmButton: false,
                            timer: 1500
                        });
        
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                });
            }
        });
    })

    $('.submitUpdateForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        console.log('formData' , formData);
        
        submitForm(formData, form);
    });

    function submitForm(formData, form) {

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: formData,
            success: function(response) {
                console.log('response', response);

                form.closest('.modal').modal('hide');
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Record has been saved",
                    showConfirmButton: false,
                    timer: 1500
                });

               setTimeout(() => {
                   window.location.reload();
               }, 2000);
            }
        });

    }
    </script>

</body>

</html>