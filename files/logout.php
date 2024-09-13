<?php
session_start();
session_destroy();
unset($_SESSION['adminLogin']);
header('location:../index.php');

?>