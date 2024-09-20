<?php 
include "files/function.php";
if(!isset($_SESSION['adminLogin'][0]) AND !isset($_SESSION['adminLogin'][1]) AND empty($_SESSION['adminLogin'][0]) AND empty($_SESSION['adminLogin'][1]))
{
	header('location:index.php');
	exit();
}

$run=$con->prepare("SELECT e.*
                     FROM `employee` e 
                     ORDER BY e.id DESC");
if($run->execute())
{
  $employeeData=$run->fetchAll(PDO::FETCH_ASSOC);
}
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

</head>

<body>

    <?php include "files/sidebar.php" ?>
    <div class="content-wrap">
        <div class="main"></div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Employee List</h5>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#employeeModal">
                    Add New Employee
                </button>
            </div>
            <?php 
    if(isset($employeeData) && is_array($employeeData)) {
    ?>
            <form class="d-flex mt-3" role="search">
                <input class="form-control me-2 searchEmployee" type="search" placeholder="Search" aria-label="Search">
            </form>
            <div class="table-responsive">
                <table class="table table-stripped text-white table-borderless mt-3">
                    <thead class="" style="color: white !important;">
                        <tr class="">
                            <th class="">ID</th>
                            <th class="">Full Name</th>
                            <th class="">Gender</th>
                            <th class="">Address</th>
                            <th class="">Phone No</th>
                            <th class="">Salary</th>

                            <th class="">Role</th>
                            <th class="">Action</th>
                        </tr>
                    </thead>
                    <tbody class="allEmployee">
                        <?php
          $count = 1;
          foreach ($employeeData as $key => $value) {
            echo '<tr>
                    <td>'.$count.'</td>
                    <td>'.ucwords($value['full_name']).'</td>
                    <td>'.ucwords($value['gender']).'</td>
                    <td>'.ucwords($value['address']).'</td>
                    <td>'.$value['phone_no'].'</td>
                    <td>'.ucwords($value['salary']).'</td>
                   
                    <td>'.ucwords($value['employee_role']).'</td>
                    <td>
                      <div class="btn-group" role="group" aria-label="Button group">
                        <button class="btn btn-success btn-sm employeeUpdateBtn" employeeID="'.$value['id'].'">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger ml-2 btn-sm deleteBtnEmployee" employeeID="'.$value['id'].'" data-toggle="modal" data-target="#employeeDelete">
                          <i class="fa fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>';
            $count++;
          }
          ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>


    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-hidden="true"><br><br>
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form action="files/employee.php" method="post" class="submitForm">
                            <div class="form-group">
                                <b>Employee Name</b>
                                <input type="text" id="txtOnly" name="employee_name" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"
                                    placeholder="Enter Employee Name....!" class="form-control mb-4 shadow rounded-0 txtOnly"
                                    required>
                            </div>

                            <div class="form-group">
                                <b>Gender : </b>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="gender" class="form-check-input" value="male"
                                            checked>Male
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" name="gender" class="form-check-input" value="female">Female
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <b>Address</b>
                                <input type="text" name="address" placeholder="Enter Address"
                                    class="form-control mb-4 shadow rounded-0" required>
                            </div>

                            <div class="form-group">
                                <b>Phone No</b>
                                <input type="number" name="phone_no" placeholder="Enter Phone No"
                                    class="form-control mb-4 shadow rounded-0" required>
                            </div>

                            <div class="form-group">
                                <b>Employee Role</b>
                                <!-- <input type="text" id="employee_role" name="employee_role"
                                    placeholder="Enter Employee Role" class="form-control mb-4 shadow rounded-0"
                                    required> -->
                                    <select name="employee_role" id="employee_role" class="form-control mb-4">
                                        <option value="teacher ">Teacher</option>
                                        <option value="gardener">Gardener</option>
                                        <option value="driver">Driver</option>
                                        <option value="chef ">chef</option>
                                        <option value="sweeper ">Sweeper</option>
                                        <option value="guard ">Guard</option>
                                    </select>
                            </div>



                            <div class="form-group">
                                <b>Monthly Salary</b>
                                <input type="number" name="salary" placeholder="Enter Monthly Salary"
                                    class="form-control mb-4 shadow rounded-0" required>
                            </div>
                            <!-- <div class="form-group">
                        <b>Duty Start</b>
                        <input type="time" id="time" name="duty_start" placeholder="from time"
                          class="form-control mb-4 shadow rounded-0" required>
                      </div>
                      <div class="form-group">
                        <b>Duty End</b>
                        <input type="time" id="time" name="duty_end" placeholder="to time"
                          class="form-control mb-4 shadow" required>
                      </div> -->

                            <button type="submit" class="btn btn-primary">Add Employee</button>

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

    <div class="modal fade" id="updateEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Employee</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <form action="files/employee.php" method="post" class="submitUpdateForm">
                            <div id="employeeUpdateForm">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Employee</button>
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


    <div class="modal fade" id="logoutID" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout ID</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Are You Sure You Want To Logout ID.?</p>
                </div>

                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-success btn-sm idLogOut">Yes</button>
                    <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
                </div>

                <div class="alertModelError"></div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="bigImage" tabindex="-1" aria-labelledby="appointmentDelete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="addImage">

                </div>

                <div class="modal-footer text-center">
                    <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>

                <div class="alertModelError"></div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="employeeDelete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Employee Delete</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Are You Sure You Want To Delete Employee.?</p>
                </div>

                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-success btn-sm yesDeleteEmployee">Yes</button>
                    <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
                </div>

                <div class="alertError"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="employeeEntry" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Entry & Exit Time</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <center>
                        <p id="current-time"></p>
                        <button class="btn btn-success">Entry</button>
                        <button class="btn btn-danger">Exit</button>
                    </center>
                </div>

                <div class="modal-footer text-center">
                    <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                </div>

                <div class="alertModelError"></div>
            </div>
        </div>
    </div>




    <!-- jquery vendor -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->

    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <!-- bootstrap -->

    <script src="js/custom.js"></script>


<script>
$(document).on('keydown', '.txtOnly', function(e) {
    if (e.shiftKey || e.ctrlKey || e.altKey) {
        e.preventDefault();
    } else {
        var key = e.keyCode;
        if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
            e.preventDefault();
        }
    }
});
</script>

</body>

</html>