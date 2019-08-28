<?php
 	ini_set('error_reporting', E_ALL);
  	ini_set('display_errors', 1);
 	ini_set('display_startup_errors', 1);
require_once("bdcon.php");
$now = date("Y-m-d");
	
$idsmena1 = DBOnce('id','brigada Order BY id DESC','');
$idsmena2 = $idsmena1 - 1;
$idsmena3 = $idsmena2 - 1;


$brigadir1 = DBOnce('brigadir','brigada','id='.$idsmena1);
$brigadir2 = DBOnce('brigadir','brigada','id='.$idsmena2);
$brigadir3 = DBOnce('brigadir','brigada','id='.$idsmena3);

$daynight1 = DBOnce('daynight','brigada','id='.$idsmena1);
$daynight2 = DBOnce('daynight','brigada','id='.$idsmena2);
$daynight3 = DBOnce('daynight','brigada','id='.$idsmena3);


$datestart = DBOnce('datestart','brigada','id='.$idsmena1);

$razn = date('d', strtotime($datestart)) - date('d', strtotime($now));
echo $idsmena1 . ':' . $brigadir1 . ' - ' . $daynight1 . '<br>' . $idsmena2 . ':' . $brigadir2 . ' - ' . $daynight2 . '<br>' . $idsmena3 . ':' . $brigadir1 . ' - ' . $daynight3 . '<br>' . $datestart;
if ($brigadir1 =='Рыкин' and $daynight1 == 'day' and $brigadir2 == 'Шитов' and $daynight2=='night' and $brigadir3=='Вилков' and $daynight3=='day' and $razn == 0) {
for ($i = 1; $i <= 12; $i++) 
{ 
  	if ($i == 1) {
	  	
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Вилков';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 2) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Рыкин';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 3) {
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Вилков';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 4) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Шитов';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 5) {
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Рыкин';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 6) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Шитов';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 7) {
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Рыкин';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 8) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Вилков';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 9) {
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Шитов';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 10) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Вилков';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 11) {
	  	$date = strtotime($datestart);
	  	$date = strtotime("+1 day", $date);
	  	$dateend = date('Y-m-d', $date);
	  	
	  	$datestart = $datestart;
	  	$timestart = '19:00:00';
	  	$dataend = $dateend;
	  	$timeend = '06:59:59';
	  	$daynight = 'night';
	  	$brigadir = 'Шитов';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	if ($i == 12) {
	  	$datestart = $dataend;
	  	$timestart = '07:00:00';
	  	$dataend = $dateend;
	  	$timeend = '18:59:59';
	  	$daynight = 'day';
	  	$brigadir = 'Рыкин';
	  	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	  	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $daynight, 'brigadir' => $brigadir));
  	}
  	
} echo 'Смены добавлены';
} else {
	echo 'Рано еще';
}


?>