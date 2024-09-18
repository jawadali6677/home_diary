<?php 
include "files/function.php";
if(isset($_SESSION['adminLogin'][0]) AND isset($_SESSION['adminLogin'][1]) AND !empty($_SESSION['adminLogin'][0]) AND !empty($_SESSION['adminLogin'][1]))
{
	header('location:employee.php');
	exit();
}

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- ** CSS Plugins Needed for the Project ** -->

  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <!--Favicon-->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
  <!-- Main Stylesheet -->
  <link href="assets/style.css" rel="stylesheet" media="screen" />

  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      max-width: 500px;
      margin-top: 100px;
      background-color: white;
      padding: 20px;
      border-radius: 25px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
      
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn {
      border-radius: 20px;
      padding: 5px 50px 5px 50px;
    }
  </style>
</head>


<body style="background-image: url(images/bg.jpg); background-size: right; overflow: hidden;">
  <br><br><br><br>
  <div class="banner bg-cover">
    <br><br>
    <section class="section">
      <div class="row">
        <div class="col-md-6 ml-5 my-5" >
          <div class="card" style="opacity: 0.8; border:1px solid black; margin-left: 20%; border-radius: 5px 35px 5px 35px;">
            <div class="card-header">
              <h2>Admin Login</h2>

              <form action="files/login.php" method="POST" class="">
                <div class="form-group">
                  <input type="text" id="email" name="login_username" placeholder="Enter username"
                    class="form-control mb-4 shadow rounded-0" style="border:1px solid black">
                </div>
                <div class="form-group">
                  <input type="password" id="pwd" name="login_pass" placeholder="Enter password"
                    class="form-control mb-4 shadow rounded-0" style="border:1px solid black">
                </div>

                <center>
                  <div class="alertError"></div>
                </center>

                <div class="row">
                  <div class="col-5">
                  </div>
                  <div class=""><button type="submit" class="btn btn-secondary" style="border:1px solid black">Login</button></div>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <div class="modal fade" id="logoutID" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

  <!-- ** JS Plugins Needed for the Project ** -->
  <!-- jquiry -->
  <script src="plugins/jquery/jquery-1.12.4.js"></script>
  <!-- Bootstrap JS -->
  <script src="plugins/bootstrap/bootstrap.min.js"></script>
  <!-- match-height JS -->
  <script src="plugins/match-height/jquery.matchHeight-min.js"></script>
  <!-- Main Script -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/custom.js"></script>
</body>