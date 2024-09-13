<?php
include "function.php";

if (isset($_POST['login_username']) AND isset($_POST['login_pass'])) {
    if (!empty($_POST['login_username']) AND !empty($_POST['login_pass'])) {
        $username = $_POST['login_username'];
        $password = $_POST['login_pass'];
        
        $run = $con->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $run->bindParam(1, $username, PDO::PARAM_STR);
        $run->bindParam(2, $password, PDO::PARAM_STR); // Changed to PARAM_STR assuming password is stored as a string
        
        if ($run->execute()) {
            if ($run->rowCount() > 0) {
                $adminData = $run->fetch(PDO::FETCH_ASSOC);
                
                $_SESSION['adminLogin'] = [
                    $username,
                    $adminData['id']
                ];
                
                // Output JavaScript for redirection
                echo '<script type="text/javascript">
                        alert("Account Login Successfully.....!");
                        window.location.href = "' . BASEURL . '/employee.php";
                      </script>';
                exit();
            } else {
                // Handle login failure
                echo '<script type="text/javascript">
                        alert("Invalid username or password.");
                        window.location.href = "' . BASEURL . '/login.php";
                      </script>';
                exit();
            }
        } else {
            // Handle query execution failure
            echo '<script type="text/javascript">
                    alert("Query execution failed.");
                    window.location.href = "' . BASEURL . '/login.php";
                  </script>';
            exit();
        }
    } else {
        echo '<script type="text/javascript">
                alert("Please enter username and password.");
                window.location.href = "' . BASEURL . '/login.php";
              </script>';
        exit();
    }
}
?>