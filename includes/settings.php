<?php 
session_start();
$_SESSION["session_username"] = $_COOKIE["session_username"];
if(!isset($_SESSION["session_username"])) {
	header("location:login.php");
} else {
?>

<?php require_once("includes/bdcon.php"); ?>
<?php $un = $_COOKIE["session_username"]; $sql = mysql_query("SELECT `username`, `id` FROM `usertbl` WHERE username='".$un."'", $link); while ($result = mysql_fetch_array($sql)) { $iduser = $result['id'];  } ?>
<html>
<head>
    <title>Задачи</title>
    <?php include 'includes/header.php';?>

		<!-- Админ -->
		<?php
			
			if($iduser == "1") {
				include 'includes/addsmenapob.php';
				include 'includes/test.php';
				//include 'includes/addsmenapob.php';
				include 'includes/addsmena.php';
				//include 'includes/tasklist.php';
			} else {
			}
		?>
		
		<!-- Zenno -->
		<?php
			if($iduser == "3") {
				include 'includes/addsmenapob.php';
				include 'includes/zenno-add-info-money.php';
			} else {
			}
		?>
				
		</div>
	</div>
</div>




<?php include("includes/footer.php"); ?>
	

<?php
}
?>
