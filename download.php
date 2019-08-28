<?php  
	header('Content-Type: application/vnd.ms-excel; charset=utf-8');
	 if ($_POST["idreport"] == 1) {
		 header("Content-Disposition: attachment;filename=".$_POST["name"].".xls");
	 } else {
		 
	 }
	 if ($_POST["idreport2"] == 1) {
		 header("Content-Disposition: attachment;filename=Справка о взвешивании вагона.xls");
	 } else {
		 
	 }
 
 header("Content-Transfer-Encoding: binary ");
 require_once("bdcon.php");
 ?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <meta name="author" content="rubezh" />
    <title>Отчет</title>
</head>
<body>
		<?php

if (isset($_POST["idreport"])) {

	//Отчет с весовой
	$timestart = $_POST["timestart"];
	$timeend = $_POST["timeend"];
	$gruz = $_POST["gruz"];
	$datestart = $_POST['datestart'];
	$dateend = $_POST['dateend'];	
	// если не выбран получатель
	if (empty($_POST["polychatel"])) {
	
	$polychatel = ''; 
	$polychatelform = '';
	$zagform = $_POST["gruz"];
	
	} else {
	// если выбран получатель
	$polychatelform = "and FIRMA_POL='".$_POST["polychatel"]."'";
	$polychatel = $_POST["polychatel"];
	$zagform = $polychatel;
	}
	
	// если не выбран груз и получатель
	if (empty($_POST["gruz"]) and empty($_POST["polychatel"])) {
		 echo '<p class="text-center mt-3">Выберите получателя и(или) груз для формирования отчета</p>';
	} else {
	
	// реализация
	$typeevent = 'Реализация (отгрузка покупателю)';
	
	// но если груз побочка, то покупка
	if ($gruz == 'Карта выборки №1 МАРТЕН' OR $gruz == 'Карта выборки №2 ЛПК' OR $gruz == 'Карта выборки №3 ДОНЫШКИ' OR $gruz == 'Оплаченный материал') {
		$typeevent = 'Покупка (приход от поставщика)';
	} 
	
	// форматирование даты
	$datestart2 = date("d.m", strtotime($datestart));
	$dateend2 = date("d.m", strtotime($dateend));
	
	// фильтр экоскрапа и другие выборки
	$eko = "GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм'";
	$typepol = "TYP_EVENT='".$typeevent."' ".$polychatelform;
	$datacreate = "DATETIME_CREATE between '".$datestart." ".$timestart.":00' and '".$dateend." ".$timeend.":59'";
	
	if (empty($_POST["gruz"])) {
	
	// если пустой груз, то ищем общее количество тонн только с получателем
	$all = DBOnce2("SUM(NETTO)","weighing",$typepol." and ".$eko." and ".$datacreate);
	$sql = "SELECT GRUZ_NAME, NETTO, NOMER_TS, TARA, DOC, FIRMA_POL, DATETIME_CREATE FROM weighing WHERE ".$typepol." and ".$eko." and ".$datacreate." ORDER BY DATETIME_CREATE";
	
	if ($datestart2 == $dateend2) {
		$period = 'Отгрузки '.$datestart2;
	} else {
		$period = 'Отгрузки с '.$datestart2.' по '.$dateend2;
	}
	
	
	} else {
	
	// если же груз не пустой, то ищем
	$all = DBOnce2("SUM(NETTO)","weighing","TYP_EVENT='".$typeevent."' and GRUZ_NAME='".$_POST["gruz"]."' ".$polychatelform." and ".$eko." and ".$datacreate);
	$sql = "SELECT GRUZ_NAME, NETTO, DOC, NOMER_TS, TARA, DATETIME_CREATE, FIRMA_POL FROM weighing WHERE TYP_EVENT='".$typeevent."' and GRUZ_NAME='".$_POST["gruz"]."' ".$polychatelform." and ".$eko." and ".$datacreate." ORDER BY DATETIME_CREATE";
	
	if ($datestart2 == $dateend2) {
		$period = 'Отгрузки груза '.$_POST["gruz"].' '.$datestart2;
	} else {
		$period = 'Отгрузки груза '.$_POST["gruz"].' с '.$datestart2.' по '.$dateend2;
	}
	
	}
	 
	 
		echo '
		<table>
			<tbody>
				<tr><td>'.$_POST["name"].'</td></tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-hover" border="1">';
		include 'includes/report/vesy-report-table.php';
		
}


}


if (isset($_POST["idreport2"])) {
$typeevent = 'Реализация (отгрузка покупателю)';
		$datestart = $_POST['datestart'];
	$dateend = $_POST['dateend'];
	$i = 0;
	$sql = "SELECT GRUZ_NAME, VESYNAME, NETTO, BRUTTO, NOMER_TS, TARA, DATETIME_CREATE FROM weighing WHERE TYP_EVENT='".$typeevent."' and VESYNAME = 'Весы 3' and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' ORDER BY DATETIME_CREATE";
	echo	'<table class="table table-hover" border="1">';
	include 'includes/report/spravka-table.php';	
}

?>
				
	
</body>

