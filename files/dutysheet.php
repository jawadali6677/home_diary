<?php
include "function.php"; // Include your database connection or utility functions

// Function to prepare response in JSON format
function prepareJsonResponse($status, $message, $data) {
    return json_encode(array(
        'status' => $status,
        'message' => $message,
        'data' => $data
    ));
}

// Handling duty sheet addition
if(isset($_POST['mon_duty_start']) && isset($_POST['mon_duty_end']) && isset($_POST['tue_duty_start']) && isset($_POST['tue_duty_end']) && isset($_POST['wed_duty_start']) && isset($_POST['wed_duty_end']) && isset($_POST['thur_duty_start']) && isset($_POST['thur_duty_end']) && isset($_POST['fri_duty_start']) && isset($_POST['fri_duty_end']) && isset($_POST['sat_duty_start']) && isset($_POST['sat_duty_end']) && isset($_POST['sun_duty_start']) && isset($_POST['sun_duty_end']) && isset($_POST['id'])) {
    
    $mon_duty_start = $_POST['mon_duty_start'];
    $mon_duty_end = $_POST['mon_duty_end'];
    $tue_duty_start = $_POST['tue_duty_start'];
    $tue_duty_end = $_POST['tue_duty_end'];
    $wed_duty_start = $_POST['wed_duty_start'];
    $wed_duty_end = $_POST['wed_duty_end'];
    $thur_duty_start = $_POST['thur_duty_start'];
    $thur_duty_end = $_POST['thur_duty_end'];
    $fri_duty_start = $_POST['fri_duty_start'];
    $fri_duty_end = $_POST['fri_duty_end'];
    $sat_duty_start = $_POST['sat_duty_start'];
    $sat_duty_end = $_POST['sat_duty_end'];
    $sun_duty_start = $_POST['sun_duty_start'];
    $sun_duty_end = $_POST['sun_duty_end'];
    $employee_id = $_POST['id'];
    
    $run = $con->prepare("INSERT INTO `duty_sheet`(`mon_duty_start`, `mon_duty_end`, `tue_duty_start`, `tue_duty_end`, `wed_duty_start`, `wed_duty_end`, `thur_duty_start`, `thur_duty_end`, `fri_duty_start`, `fri_duty_end`, `sat_duty_start`, `sat_duty_end`, `sun_duty_start`, `sun_duty_end`, `employee_id`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    if($run && $run->execute([$mon_duty_start, $mon_duty_end, $tue_duty_start, $tue_duty_end, $wed_duty_start, $wed_duty_end, $thur_duty_start, $thur_duty_end, $fri_duty_start, $fri_duty_end, $sat_duty_start, $sat_duty_end, $sun_duty_start, $sun_duty_end, $employee_id])) {
        echo prepareJsonResponse('success', 'Dutysheet added successfully', '');
    } else {
        echo prepareJsonResponse('error', 'Failed to add dutysheet', '');
    }
}

// Handling duty sheet deletion
else if(isset($_POST['dutysheetDelete']) && isset($_POST['dutysheetID'])) {
    $dutysheetID = (int)$_POST['dutysheetID'];
    if(!empty($dutysheetID) && is_int($dutysheetID)) {
        $run = $con->prepare("SELECT * FROM `duty_sheet` WHERE duty_id=?");
        $run->execute([$dutysheetID]);
        if($run->rowCount() > 0) {
            $delete = $con->prepare("DELETE FROM `duty_sheet` WHERE duty_id=?");
            if($delete && $delete->execute([$dutysheetID])) {
                echo prepareJsonResponse('success', 'Dutysheet deleted successfully', '');
            } else {
                echo prepareJsonResponse('error', 'Failed to delete dutysheet', '');
            }
        } else {
            echo prepareJsonResponse('error', 'Invalid dutysheet deletion attempt', '');
        }
    } else {
        echo prepareJsonResponse('error', 'Invalid dutysheet ID', '');
    }
}

// Handling duty sheet retrieval for update
else if(isset($_POST['dutysheetData']) && isset($_POST['dutysheetId'])) {
    $dutysheetID = (int)$_POST['dutysheetId'];
    if(!empty($dutysheetID)) {
        $run = $con->prepare("SELECT * FROM `duty_sheet` WHERE duty_id=?");
        $run->execute([$dutysheetID]);
        
        if($run->rowCount() > 0) {
            $data = $run->fetch(PDO::FETCH_ASSOC);
            
            // Prepare HTML for duty sheet update form
            $html = '<div class="form-group">
                <b>Mon Duty Start</b>
                <input type="time" name="update_mon_duty_start" value="'.$data['mon_duty_start'].'" class="form-control" required>
            </div>
            <div class="form-group">
                <b>Mon Duty End</b>
                <input type="time" name="update_mon_duty_end" value="'.$data['mon_duty_end'].'" class="form-control" required>
            </div>
            <div class="form-group">
                <b>Tue Duty Start</b>
                <input type="time" name="update_tue_duty_start" value="'.$data['tue_duty_start'].'" class="form-control" required>
            </div>
            <div class="form-group">
                <b>Tue Duty End</b>
                <input type="time" name="update_tue_duty_end" value="'.$data['tue_duty_end'].'" class="form-control" required>
            </div>
            <!-- Repeat for other days as needed -->';

            echo prepareJsonResponse('success', 'Dutysheet data retrieved successfully', $html);
        } else {
            echo prepareJsonResponse('error', 'Dutysheet data not found', '');
        }
    } else {
        echo prepareJsonResponse('error', 'Invalid dutysheet ID', '');
    }
}elseif(isset($_POST['user_id']) && isset($_POST['editDuty'])){
    $user_id = $_POST['user_id'];

    if (!empty($user_id)) {
        // Fetching records from the database
        $run = $con->prepare("SELECT * FROM `duty_sheet` WHERE employee_id=?");
        $run->execute([$user_id]);
        $records = $run->fetchAll(PDO::FETCH_ASSOC);
    
        // Preparing the duty array
        $duty = [];
        foreach ($records as $data) {
            $duty[$data['day']] = [
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time']
            ];
        }
    
        // Days of the week array
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
        
        $html = '';
        foreach ($days as $index => $day) {
            $checked = !empty($duty[$day]) ? "checked" : "";
            $start_time = !empty($duty[$day]) ? $duty[$day]['start_time'] : "";
            $end_time = !empty($duty[$day]) ? $duty[$day]['end_time'] : "";
    
            $html .= '                    
                <tr>
                    <td><input type="checkbox" name="duty_sheet[' . $index . '][day]" ' . $checked . ' value="' . $day . '"> ' . $day . '</td>
                    <td><input type="time" name="duty_sheet[' . $index . '][start_time]" value="' . $start_time . '" class="form-control mb-4 shadow"></td>
                    <td><input type="time" name="duty_sheet[' . $index . '][end_time]" value="' . $end_time . '" class="form-control mb-4 shadow"></td>
                </tr>';
        }

        echo json_encode(array(
            'status' => "success",
            'data' => $html
        ) , 200);
    }else{
        echo json_encode(['success' => false , 'message' => 'provide user id'] , 500);
    }
}

elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_duty'])) {

    try {
        
            // print_r($_POST); exit;

            $id = $_POST['id'];
            $user_id = $_POST['user_id'];

            foreach($_POST['duty_sheet'] as $key=> $duty){
                if(isset($duty['day'])){

                    $day = $duty['day'];
                    
                    $sql = "SELECT * 
                            FROM `duty_sheet`
                            WHERE `employee_id`= $user_id AND `day`= '$day'";

                    $run = $con->prepare($sql);
                    $run->execute();

                    $record = $run->fetchAll(PDO::FETCH_ASSOC);

                    $start_time = $duty["start_time"];
                    $end_time = $duty["end_time"];
                    
                    if(!empty($record)){
            
                        $run=$con->prepare('UPDATE `duty_sheet` SET `start_time`=? , `end_time`=? WHERE `day`=? AND `employee_id`=?');
                        $run->bindParam(1,$start_time,PDO::PARAM_STR);
                        $run->bindParam(2,$end_time,PDO::PARAM_STR);
                        $run->bindParam(3,$duty['day'],PDO::PARAM_STR);
                        $run->bindParam(4,$user_id,PDO::PARAM_INT);
                        
                        $run->execute();

                    }else{

                        $run = $con->prepare("INSERT INTO `duty_sheet`(`start_time`, `end_time`, `day` , `employee_id`) VALUES (?,?,?,?)");
                        $run->execute([$start_time, $end_time, $day, $user_id]);
                    }
                }
            }

            echo json_encode(array(
                'success' => true,
                'message' => "Duty Updated Successfully."
            ) , 200);

    } catch (\Throwable $th) {
        echo json_encode(array(
            'success' => true,
            'message' => "Something went wrong."
        ) , 500);
    }

}
?>
