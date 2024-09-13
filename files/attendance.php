<?php
include "function.php";

if(isset($_POST['employee_name']) AND isset($_POST['gender']) AND isset($_POST['address']) AND isset($_POST['phone_no']) AND isset($_POST['employee_role']))
{
    $employee_name=$_POST['employee_name'];
    $gender=$_POST['gender'];
    $address=$_POST['address'];
    $phone_no=$_POST['phone_no'];
    $employee_role=$_POST['employee_role'];
  
    


    $run=$con->prepare("INSERT INTO `employee`(`full_name`, `gender`, `address`, `phone_no`, `employee_role`) VALUES (?,?,?,?,?)");
    if(is_object($run))
    {
        $run->bindParam(1,$employee_name,PDO::PARAM_STR);
        $run->bindParam(2,$gender,PDO::PARAM_STR);
        $run->bindParam(3,$address,PDO::PARAM_STR);
        $run->bindParam(4,$phone_no,PDO::PARAM_STR);
        $run->bindParam(5,$employee_role,PDO::PARAM_STR);
       
       


        if($run->execute())
        {
            return MsgDisplay('refersh','Employee Added Successfully.....!','');
        }
        
    }
}
else if(isset($_POST['employeeDelete']) AND isset($_POST['employeeID']))
{
    $employeeID = (int)$_POST['employeeID'];
    if(!empty($employeeID) AND is_int($employeeID))
    {
        $run=$con->prepare("Select * FROM `employee` WHERE employee_id=?");
        $run->bindParam(1,$employeeID,PDO::PARAM_INT);
        if($run->execute())
        {
            if($run->rowCount()>0)
            {

                $run=$con->prepare("DELETE FROM `employee` WHERE employee_id=?");
                $run->bindParam(1,$employeeID,PDO::PARAM_INT);
                $run->execute();
                
                return MsgDisplay('refersh','Employee Deleted Successfully.....!','');
            }
            else
            {
                return MsgDisplay('error','The attempted deletion of an employee is invalid.....!','');
            }
        }

        
    }
}
else if(isset($_POST['employeeData']) AND isset($_POST['employeeId']))
{
    if(!empty($_POST['employeeId']))
    {
        $employeeID=$_POST['employeeId'];

        $run=$con->prepare("SELECT * FROM `employee` WHERE employee_id=?");
        $run->bindParam(1,$employeeID,PDO::PARAM_INT);
        if($run->execute())
        {
            if($run->rowCount()>0)
            {
                $data=$run->fetch(PDO::FETCH_ASSOC);

                if($data['gender']=='male')
                {
                    $gender='<div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="male" checked>Male
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="female">Female
                    </label>
                  </div>';
                }
                else
                {
                    $gender='<div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="male">Male
                    </label>
                  </div>
                  <div class="form-check-inline">
                    <label class="form-check-label">
                      <input type="radio" name="update_gender" class="form-check-input" value="female" checked>Female
                    </label>
                  </div>';
                }

                $html='<div class="form-group">
                <b>Employee Name</b>
                <input type="text" id="Events" value="'.$data['full_name'].'" name="update_employee_name" placeholder="Enter Employee Name....!"
                  class="form-control mb-4 shadow rounded-0" required>
                  <input type="number"  name="employee_id" value="'.$data['employee_id'].'" hidden>
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
                <input type="text" name="update_phone_no" value="'.$data['phone_no'].'" placeholder="Enter Phone No"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>

              <div class="form-group">
                <b>Employee Role</b>
                <input type="text" name="update_employee_role" value="'.$data['employee_role'].'" placeholder="Enter Employee Role"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>

              <div class="form-group">
                <b>Monthly Salary</b>
                <input type="number" name="update_salary" value="'.$data['monthly_salary'].'" placeholder="Enter Monthly Salary"
                  class="form-control mb-4 shadow rounded-0" required>
              </div>

              <div class="form-group">
                <b>Duty Start</b>
                <input type="time" id="time" value="'.$data['duty_start'].'" name="update_duty_start" placeholder="from time"
                    class="form-control mb-4 shadow rounded-0" required>
                </div>
                <div class="form-group">
                <b>Duty End</b>
                <input type="time" id="time" value="'.$data['duty_end'].'" name="update_duty_end" placeholder="to time"
                    class="form-control mb-4 shadow" required>
                </div>';

                return MsgDisplay('success',$html,'');
            }
        }
    }
}
else if(isset($_POST['update_employee_name']) AND isset($_POST['update_gender']) AND isset($_POST['update_address']) AND isset($_POST['update_phone_no']) AND isset($_POST['update_employee_role']) AND isset($_POST['update_salary']) AND isset($_POST['update_duty_start']) AND isset($_POST['update_duty_end']))
{
    //update employee
    $employee_id=$_POST['employee_id'];
    $name=$_POST['update_employee_name'];
    $gender=$_POST['update_gender'];
    $address=$_POST['update_address'];
    $phone_no=$_POST['update_phone_no'];
    $role=$_POST['update_employee_role'];
    $salary=$_POST['update_salary'];

    $duty_start=$_POST['update_duty_start'];
    $duty_end=$_POST['update_duty_end'];
    

    if(!empty($employee_id))
    {
        $run=$con->prepare("SELECT * FROM `employee` WHERE employee_id=?");
        $run->bindParam(1,$employee_id,PDO::PARAM_INT);
        if($run->execute())
        {
            if($run->rowCount()>0)
            {
                $oldData=$run->fetch(PDO::FETCH_ASSOC);

                if(empty($name))
                {
                    $name=$oldData['full_name'];
                }
                else if(empty($gender))
                {
                    $gender=$oldData['gender'];
                }
                else if(empty($address))
                {
                    $address=$oldData['address'];
                }
                else if(empty($phone_no))
                {
                    $phone_no=$oldData['phone_no'];
                }
                else if(empty($role))
                {
                    $role=$oldData['employee_role'];
                }
                else if(empty($salary))
                {
                    $salary=$oldData['monthly_salary'];
                }
                else if(empty($duty_start))
                {
                    $duty_start=$oldData['duty_start'];
                }
                else if(empty($duty_end))
                {
                    $duty_end=$oldData['duty_end'];
                }
                
             
                    $run=$con->prepare('UPDATE `employee` SET `full_name`=?,`gender`=?,`address`=?,`phone_no`=?,`employee_role`=?,`monthly_salary`=?, `duty_start`=?, `duty_end`=? WHERE employee_id=?');

                    $run->bindParam(1,$name,PDO::PARAM_STR);
                    $run->bindParam(2,$gender,PDO::PARAM_STR);
                    $run->bindParam(3,$address,PDO::PARAM_STR);
                    $run->bindParam(4,$phone_no,PDO::PARAM_STR);
                    $run->bindParam(5,$role,PDO::PARAM_STR);
                    $run->bindParam(6,$salary,PDO::PARAM_INT);
                    $run->bindParam(7,$duty_start,PDO::PARAM_STR);
                    $run->bindParam(8,$duty_end,PDO::PARAM_STR);
                    $run->bindParam(9,$employee_id,PDO::PARAM_INT);

                    if($run->execute())
                    {
                        return MsgDisplay('refersh','Employee Updated Successfully.....!','');
                    }
                 
            }
            else
            {
                return MsgDisplay('error','The attempted updated of an employee is invalid.....!','');
            }
        }
    }

    
}else if(isset($_POST['checkIn'])){


    $employee_id     = $_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $check_in_time   = $_POST['check_in_time'];
    $status          = $_POST['status'];
    
    $run = $con->prepare("INSERT INTO `attendance`(`attendance_date` , `check_in_at` , `employee_id` , `status`) VALUES (?,?,?,?)");
    if($run && $run->execute([$attendance_date, $check_in_time, $employee_id, $status])) {
        echo MsgDisplay('success', 'Check In Addedd Successfully.....!', '');
    }else{
        echo MsgDisplay('error' , 'Something went wrong.');
    }
}else if(isset($_POST['checkOut'])){

    $employee_id     = $_POST['employee_id'];
    $id = $_POST['attendance_Id'];
    $check_out_time   = $_POST['check_out_time'];

    $run=$con->prepare('UPDATE `attendance` SET `check_out_at`=? WHERE `employee_id`=? AND `id`=?');

    $run->bindParam(1,$check_out_time,PDO::PARAM_STR);
    $run->bindParam(2,$employee_id,PDO::PARAM_INT);
    $run->bindParam(3,$id,PDO::PARAM_INT);

    if($run && $run->execute()) {
        echo MsgDisplay('success', 'Check Out Addedd Successfully.....!', '');
    }else{
        echo MsgDisplay('error' , 'Something went wrong.');
    }
}else if(isset($_POST['absent']) && isset($_POST['emp_id'])){
    // print_r($_POST); exit;
    $employee_id     = $_POST['emp_id'];
    $date = $_POST['date'];
    $status = "absent";

    $run = $con->prepare("INSERT INTO `attendance`(`attendance_date` , `employee_id` , `status`) VALUES (?,?,?)");

    if($run && $run->execute([$date , $employee_id  , $status])) {

        echo MsgDisplay('success', 'Check Out Addedd Successfully.....!', '');
    }else{
        echo MsgDisplay('error' , 'Something went wrong.');
    }
}


?>