<?php
	//Отчет с весовой
	$timestart = $_POST["timestart"];
	$timeend = $_POST["timeend"];
	$gruz = $_POST["gruz"];
	
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
	 
	 
	
	if ($all > 0) {
	
	
	$zag = '<div class="zag">
				<div class="float-left">
					<h4>'.$zagform.'</h4>
					<p>'.$period.'</p>
				</div>
				<form method="post" action="/download.php" target="_blank" class="float-right">
				    <input type="date" class="form-control hidden" name="datestart" value="'.$datestart.'">
				    <input type="time" class="form-control hidden" name="timestart" value="'.$timestart.'">
				    <input type="date" class="form-control hidden" name="dateend" value="'.$dateend.'">
				    <input type="time" class="form-control hidden" name="timeend" value="'.$timeend.'">
				    <input type="text" class="form-control hidden" name="gruz" value="'.$gruz.'">
				    <input type="text" class="form-control hidden" name="polychatel" value="'.$polychatel.'">
				    <input type="text" class="form-control hidden" name="idreport" value="1">
				    <input type="text" class="form-control hidden" name="name" value="'.$polychatel.' '.$period.'">
			    <button type="submit" class="btn btn-primary">Скачать отчет <i class="fas fa-download ml-2"></i></button>
			    </form><div class="clear"></div>
			</div>';
	
	echo $zag.'<div class="table-responsive"><table class="table table-hover table-bordered table-sm table-striped mt-3">';
	include 'vesy-report-table.php';
	echo '</div>';
		}
		 else  {
			 echo '<p class="text-center">Данных по данному запросу нет</p>';
		 }
		 
		if ($iduser != 1) {
		 			// запись в лог
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = "Сформировал отчет ".$polychatel." ".$period;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
		}
		}
		
		
?>