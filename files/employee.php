<?php
include "function.php";

if(isset($_POST['employee_name']) && isset($_POST['gender']) && isset($_POST['address']) && isset($_POST['phone_no']) && isset($_POST['salary']) && isset($_POST['employee_role'])) {
    $employee_name = $_POST['employee_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_no = $_POST['phone_no'];
    $salary = $_POST['salary'];
    $employee_role = $_POST['employee_role'];

    $run = $con->prepare("INSERT INTO `employee`(`full_name`, `gender`, `address`, `phone_no`, `salary`, `employee_role`) VALUES (?,?,?,?,?,?)");
    if($run && $run->execute([$employee_name, $gender, $address, $phone_no, $salary, $employee_role])) {
        echo MsgDisplay('success', 'Employee Added Successfully.....!', '');
    } else {
        echo MsgDisplay('error', 'Failed to add employee.', '');
    }
} else if(isset($_POST['employeeDelete']) && isset($_POST['employeeID'])) {
    $employeeID = (int)$_POST['employeeID'];
    if(!empty($employeeID) && is_int($employeeID)) {
        $run = $con->prepare("SELECT * FROM `employee` WHERE id=?");
        $run->execute([$employeeID]);
        if($run->rowCount() > 0) {
            $delete = $con->prepare("DELETE FROM `employee` WHERE id=?");
            if($delete && $delete->execute([$employeeID])) {
              
                $delete = $con->prepare("DELETE FROM `duty_sheet` WHERE employee_id=?");
                $delete->execute([$employeeID]);
                echo MsgDisplay('success', 'Employee Deleted Successfully.....!', '');
            } else {
                echo MsgDisplay('error', 'Failed to delete employee.', '');
             }
        } else {
            echo MsgDisplay('error', 'Invalid employee deletion attempt.', '');
        }
    }
}else if(isset($_POST['employeeData']) && isset($_POST['employeeId'])) {
    $employeeID = (int)$_POST['employeeId'];
    // die ('employeeData '. $_POST['employeeData']);
    if(!empty($employeeID)) {
        $run = $con->prepare("SELECT * FROM `employee` WHERE id=?");
        $run->execute([$employeeID]);
        if($run->rowCount() > 0) {
            $data = $run->fetch(PDO::FETCH_ASSOC);

            $gender = $data['gender'] == 'male' ?
                '<div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="male" checked>Male
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="female">Female
                    </label>
                  </div>' :
                '<div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="male">Male
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="female" checked>Female
                    </label>
                  </div>';

            $html = '<div class="form-group">
                <b>Employee Name</b>
                <input type="text" id="Events" value="'.$data['full_name'].'" name="update_employee_name" placeholder="Enter Employee Name....!"
                  class="form-control mb-4 shadow rounded-0 txtOnly" required>
                  <input type="number"  name="employee_id" value="'.$data['id'].'" hidden>
              </div>

              <div class="form-group">
                <b>Gender : </b>
              '.$gender.'
              </div>
              <div class="form-group">
                <b>Address</b>
                <input type="text" name="update_address" value="'.$data['address'].'" placeholder="Enter Address"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>

              <div class="form-group">
                <b>Phone No</b>
                <input type="number" name="update_phone_no" value="'.$data['phone_no'].'" placeholder="Enter Phone No"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>

              <div class="form-group">
                <b>Employee Role</b>
                  <select name="update_employee_role" id="update_employee_role" class="form-control mb-4" required>
                      <option value="gardener" '.($data['employee_role'] == "gardener" ? 'selected' : '').'>Gardener</option>
                <option value="driver" '.($data['employee_role'] == "driver" ? 'selected' : '').'>Driver</option>
                <option value="chef" '.($data['employee_role'] == "chef" ? 'selected' : '').'>Chef</option>
                <option value="sweeper" '.($data['employee_role'] == "sweeper" ? 'selected' : '').'>Sweeper</option>
                <option value="guard" '.($data['employee_role'] == "guard" ? 'selected' : '').'>Guard</option>
                <option value="teacher" '.($data['employee_role'] == "teacher" ? 'selected' : '').'>Teacher</option>
                  </select>
              </div>

              <div class="form-group">
                <b>Monthly Salary</b>
                <input type="number" name="update_salary" value="'.$data['salary'].'" placeholder="Enter Monthly Salary"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>';

              // <div class="form-group">
              //   <b>Duty Start</b>
              //   <input type="time" id="time" value="'.$data['duty_start'].'" name="update_duty_start" placeholder="from time"
              //       class="form-control mb-4 shadow rounded-0" required>
              //   </div>
              //   <div class="form-group">
              //   <b>Duty End</b>
              //   <input type="time" id="time" value="'.$data['duty_end'].'" name="update_duty_end" placeholder="to time"
              //       class="form-control mb-4 shadow" required>
              //   </div>';

            echo MsgDisplay('success', $html, '');
        } else {
            echo MsgDisplay('error', 'Employee data not found.', '');
        }
    }
} else if(isset($_POST['update_employee_name']) && isset($_POST['update_gender']) && isset($_POST['update_address']) && isset($_POST['update_phone_no']) && isset($_POST['update_employee_role']) && isset($_POST['update_salary'])) {
    $employee_id = (int)$_POST['employee_id'];
    $name = $_POST['update_employee_name'];
    $gender = $_POST['update_gender'];
    $address = $_POST['update_address'];
    $phone_no = $_POST['update_phone_no'];
    $role = $_POST['update_employee_role'];
    $salary = $_POST['update_salary'];
    // $duty_start = $_POST['update_duty_start'];
    // $duty_end = $_POST['update_duty_end'];

    $run = $con->prepare("SELECT * FROM `employee` WHERE id=?");
    $run->execute([$employee_id]);
    if($run->rowCount() > 0) {
        $oldData = $run->fetch(PDO::FETCH_ASSOC);

        $name = !empty($name) ? $name : $oldData['full_name'];
        $gender = !empty($gender) ? $gender : $oldData['gender'];
        $address = !empty($address) ? $address : $oldData['address'];
        $phone_no = !empty($phone_no) ? $phone_no : $oldData['phone_no'];
        $role = !empty($role) ? $role : $oldData['employee_role'];
        $salary = !empty($salary) ? $salary : $oldData['salary'];
        // $duty_start = !empty($duty_start) ? $duty_start : $oldData['duty_start'];
        // $duty_end = !empty($duty_end) ? $duty_end : $oldData['duty_end'];
        
        $update = $con->prepare("UPDATE `employee` SET `full_name`=?,`gender`=?,`address`=?,`phone_no`=?,`employee_role`=?,`salary`=?WHERE id=?");
        if($update && $update->execute([$name, $gender, $address, $phone_no, $role, $salary, $employee_id])) {
            echo MsgDisplay('success', 'Employee Updated Successfully.....!', '');
        } else {
            echo MsgDisplay('error', 'Failed to update employee.', '');
        }
    } else {
        echo MsgDisplay('error', 'Invalid employee update attempt.', '');
    }
}else if(isset($_GET['emp_id'])){

  header('Content-Type: application/json');

  $employeeID = (int)$_GET['emp_id'];

  $run = $con->prepare("SELECT * FROM `employee` WHERE id=?");
  $run->execute([$employeeID]);
  $data = $run->fetch(PDO::FETCH_ASSOC);

  echo json_encode(['success' => true , 'employee' => $data], 200);
  exit;
}
?>