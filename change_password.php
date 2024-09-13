<?php 
include "files/function.php";
if(!isset($_SESSION['adminLogin'][0]) AND !isset($_SESSION['adminLogin'][1]) AND empty($_SESSION['adminLogin'][0]) AND empty($_SESSION['adminLogin'][1]))
{
	header('location:index.php');
	exit();
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
  <title>Dashboard</title>

 <!-- ================= Favicon ================== -->
 <link href="css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">


</head>

<body>

<?php include "files/sidebar.php" ?>

  <div class="content-wrap">
    <div class="main"></div>
    <section class="section">
      <div class="row">
        <div class="col-md-6 mx-auto my-5">
          <div class="card">
            <div class="card-header">
              <center>
                <h2>Change Password</h2><br>
              </center>

              <form action="files/changePassword.php" method="POST" class="submitForm">
                <div class="form-group">
                  <input type="password" name="old_password" placeholder="Enter Old Password....!"
                    class="form-control mb-4 shadow rounded-0">
                </div>
                <div class="form-group">
                  <input type="password" name="new_password" placeholder="Enter New Password....!"
                    class="form-control mb-4 shadow rounded-0">
                </div>
                <div class="form-group">
                  <input type="password" name="confirm_password" placeholder="Enter Confirm Password....!"
                    class="form-control mb-4 shadow rounded-0">
                </div>

                <div class="row">
                  <div class="col-5">

                  </div>
                  <div class=""><button type="submit" class="btn btn-primary">Change Password</button></div>
                </div>

                <center>
                  <div class="alertError"></div>
                </center>

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






  <!-- jquery vendor -->
  <script src="js/lib/jquery.min.js"></script>
  <script src="js/lib/jquery.nanoscroller.min.js"></script>
  <!-- nano scroller -->
  <script src="js/lib/menubar/sidebar.js"></script>
  <script src="js/lib/preloader/pace.min.js"></script>
  <!-- sidebar -->

  <script src="js/lib/bootstrap.min.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/custom.js"></script>
  <!-- bootstrap -->


</body>

</html>