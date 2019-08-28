<?php session_start();?>
<?php require_once("bdcon.php"); ?>
<?php

if(isset($_SESSION["session_username"])){
// echo "Session is set"; // for testing purposes
header("Location: index.php");
}
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if(!empty($_POST['username']) && !empty($_POST['password'])) {
	
	// принимаем логин и пароль
    $username=$_POST['username'];
    $password=$_POST['password'];
   
	// ищем количество строк с таким логином и паролем
    $countrow = DBOnce('COUNT(*) as count','usertbl','username="'.$username.'" and password="'.$password.'"');
    
    if($countrow!=0) {
     	
     	// сверяемся еще раз
     	$dbusername= DBOnce('username','usertbl','username="'.$username.'" and password="'.$password.'"');
	 	$dbpassword= DBOnce('password','usertbl','username="'.$username.'" and password="'.$password.'"');
     	
     	if($username == $dbusername && $password == $dbpassword) {
		 	$iduser= DBOnce('id','usertbl','username="'.$username.'" and password="'.$password.'"');
			setcookie("session_username",$username, time() + 3600*24*30*365);
			setcookie("iduser",$iduser, time() + 3600*24*30*365);
			$activity = $pdo->prepare('UPDATE `usertbl` SET activity = :activity where id="'.$iduser.'"');
			$activity->execute(array('activity' => $datetime));
			header("location:/");
     	}
     	
    } else {

 $message =  "Неверное имя или пароль!";
    }

} else {
    
}
?>

<html>
	<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/style.css?ver=20">
<script src="/js/jquery-1.11.0.min.js"></script> 
<script src="/js/bootstrap.min.js"></script> <!-- htt p:/ /netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js -->
<script src="/js/custom.js"></script>
<script src='/js/sweetalert2.all.js'></script>
<script src='/js/polyfill.min.js'></script>
<link rel="stylesheet" href="/css/swiper.min.css">
<script src="/js/swiper.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-2 hidden-sm hidden-xs"></div>
		<div class="col-md-10 col-sm-12">




<div class="info-block">
	<div class="header-block">
		<h3>Авторизация</h3>
	</div>
<div class="inside">
<form name="loginform" id="loginform" action="" method="POST">
	<div class="form-group row">
      <label for="user_login" class="col-sm-3 col-form-label">Логин</label>
      <div class="col-sm-9">
        <input type="text" name="username" id="username" value="" class="form-control" placeholder="Логин"/>
      </div>
    </div>
    <div class="form-group row">
      <label for="user_pass" class="col-sm-3 col-form-label">Пароль</label>
      <div class="col-sm-9">
        <input type="password" name="password" id="password" value="" class="form-control" placeholder="Пароль" />      
       </div>
    </div>
    
    
    <button type="submit" name="login" class="btn btn-primary">Войти</button>
</form>
</div>
</div>
<p class="text-black-50 mt-5">Забыли логин или пароль? Напишите в whatsapp +7 926 655 69 88</p>
		</div>
	</div>
</div>
	
	<?php include("footer.php"); ?>
	
	<?php if (!empty($message)) {echo "<p class=\"error\">" . "MESSAGE: ". $message . "</p>";} ?>
	