<?php
// Include database connection or setup $con variable
include "function.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dutySheetId'])) {
  $dutySheetId = $_POST['dutySheetId'];

  // Perform deletion query
  $stmt = $con->prepare("DELETE FROM `duty_sheet` WHERE `duty_id` = ?");
  if ($stmt->execute([$dutySheetId])) {
    // Deletion successful
    echo "Duty sheet deleted successfully";
    // You may echo or return a response depending on your needs
    exit;
  } else {
    // Deletion failed
    echo "Failed to delete duty sheet";
    // You may echo or return a response depending on your needs
    exit;
  }
} else {
  // Handle case where dutySheetId is not provided or method is not POST
  echo "Invalid request";
  exit;
}
?>