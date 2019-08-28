<?php 
session_start();
setcookie('session_username', '', time()); 
unset($_SESSION['session_username']);
session_destroy();
header("location:login.php");
?>
