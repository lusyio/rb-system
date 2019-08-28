<?php
$vyborka = '(GRUZ_NAME = "СКРАП 15-50" OR GRUZ_NAME = "СКРАП 50-400 мм" OR GRUZ_NAME = "СКРАП 400 плюс" OR GRUZ_NAME = "СКРАП 500-800 мм" OR GRUZ_NAME = "Зашлакованный скрап.25А")';


$scrapcar = DBOnce2('COUNT(*) as count','weighing',$vyborka.' and VESYNAME != "Весы 3" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
$scrapvagon = DBOnce2('COUNT(*) as count','weighing',$vyborka.' and VESYNAME = "Весы 3" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);

$carss = array(2,3,4, 22, 23, 24, 32, 33, 34);
$carss2 = array(1, 21, 31, 41, 51, 61, 71);
if (in_array($scrapcar, $carss)) {
				$carname = 'машины';
			} else {
				$carname = 'машин';
			}
			if (in_array($scrapcar, $carss2)) {
				$carname = 'машину';
			}
if ($scrapcar > 0) {
	$ves = 'VESYNAME != "Весы 3"';
	$type = 'TYP_EVENT="Реализация (отгрузка покупателю)"';
	$scrap = DBOnce2('SUM(NETTO)','weighing',$vyborka.' and '.$ves.' and '.$type.' and DATETIME_CREATE '.$bwnow);
	echo '<p>Отгрузили '.$scrapcar.' '.$carname.' СКРАПа ('.numb($scrap/1000).'т.):</p>';
	$sql = 'SELECT SUM(NETTO), GRUZ_NAME from weighing WHERE '.$vyborka.' and '.$ves.' and '.$type.' and DATETIME_CREATE '.$bwnow.' GROUP BY GRUZ_NAME';
	$sql = $pdoves->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
	foreach ($sql as $result) {
		$ves = numb($result['SUM(NETTO)']/1000);
		$gruz = $result['GRUZ_NAME'];
		echo '<p><strong>'.$ves.' т. '.$gruz.'</strong>: ';
		$gruz = $result['GRUZ_NAME'];
		$i = 0;
		$sql2 = 'SELECT COUNT(*) as count, FIRMA_POL FROM weighing WHERE GRUZ_NAME = "'.$gruz.'" and '.$type.' and '.$ves.' and DATETIME_CREATE '.$bwnow.' GROUP BY FIRMA_POL';
		$sql2 = $pdoves->prepare($sql2);
		$sql2->execute();
		$sql2 = $sql2->fetchAll(PDO::FETCH_BOTH);
		foreach ($sql2 as $result2) {
			$i = $i + 1;
			if ($i == 1) { $zp =' ';} else { $zp = ', ';}
			$counts = $result2['count'];
			
			
			if (in_array($counts, $carss)) {
				$carname = 'машины';
			} else {
				$carname = 'машин';
			}
			if (in_array($counts, $carss2)) {
				$carname = 'машину';
			}
			if ($counts == 1) { $carname = 'машина';}
			echo $zp.$counts.' '.$carname.' для '.$result2['FIRMA_POL'];		
		}
	}
}
if ($scrapvagon > 0) {
	$vagons = array(2,3,4, 22, 23, 24, 32, 33, 34);
	if (in_array($scrapvagon, $vagons)) {
		$vagonname = 'вагона';
	} else {
		$vagonname = 'вагонов';
	}
	if ($scrapvagon == 1) { $vagonname = 'вагон';}	
	$ves = 'VESYNAME = "Весы 3"';
	$type = 'TYP_EVENT="Реализация (отгрузка покупателю)"';
	$scrap = DBOnce2('SUM(NETTO)','weighing',$vyborka.' and '.$ves.' and '.$type.' and DATETIME_CREATE '.$bwnow);
	echo '<p>Отгрузили '.$scrapvagon.' '.$vagonname.' СКРАПа ('.numb($scrap/1000).'т.).</p>';
}
?>