<?php
		
	$userv = 'rubezh';
	$passv = 'rubezh';
	$pdoves = new PDO('mysql:host=server-auto.com;port=336;charset=utf8;dbname=rubezh2all', $userv, $passv);

	$user = 'richbeer_rub';
	$pass = 'Metro2033228';
	$pdo = new PDO('mysql:host=localhost;charset=utf8;dbname=richbeer_rub', $user, $pass);
	
	$now = date("Y-m-d"); 
	$month = date("Y-m-01");
	$yesterday = Date("Y-m-d",strtotime("-1 day")); 
	$datetime = date("Y-m-d H:i:s");
	$bwnow = 'between "'.$now.' 00:00:00" and "'.$now.' 23:59:59"';
	$bwmonth = 'between "'.$month.' 00:00:00" and "'.$now.' 23:59:59"';
	

	
	function DBOnce($zapros,$db,$where) {
		global $pdo;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where.' limit 1';
		$row = $pdo->query($sql);
		// Перебор и вывод результатов
		$result = $row->fetch();
		return $result[0];
	}
	
	function DBOnce2($zapros,$db,$where) {
		global $pdoves;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where.' limit 1';
		$row = $pdoves->query($sql);
		// Перебор и вывод результатов
		$result = $row->fetch();
		return $result[0];
	}
	
	// формирование массива из базы данных для дальнейшего вывода в цикле
	function DB($zapros,$db,$where) {
		global $pdo;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where;
		$sql = $pdo->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		return $sql;
	}
	
	function DB2($zapros,$db,$where) {
		global $pdoves;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where;
		$sql = $pdoves->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		return $sql;
	}
	
	function successmes($url) {
		echo "<script>
			   		swal({
	  title: 'Успешно',
	  text: '',
	  type: 'success',
	  allowOutsideClick: false,
	}).then(function (result) {
	  if (result.value) {
	    window.location = '".$url."';
	  }
	})
			   </script>";
	}	
	
	function errormes($url) {
		echo "<script>
			   		swal({
	  title: 'Произошла ошибка',
	  text: '',
	  type: 'error',
	  allowOutsideClick: false,
	}).then(function (result) {
	  if (result.value) {
	    window.location = '".$url."';
	  }
	})
			   </script>";
	}
	
	function numb($number) {
		return number_format($number,0,'',' ');
	}
	
	function okr($number) {
		return number_format($number,0,'','');
	}
	
 	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
 	ini_set('display_startup_errors', 1);
?>