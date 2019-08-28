
<?php if ($iduser == 1 or $iduser == 2 or $iduser == 4) {
  	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
  	ini_set('display_startup_errors', 1);
$text = 'This is a test'; 

echo substr_count($text, 'is'); // 2
 
$p = 1;
// ЭКО
$typeevent = 'Реализация (отгрузка покупателю)';
$datestart = '2018-11-22';
$dateend = '2018-11-25';
	
$vyborka = '(GRUZ_NAME = "ЭКОСКРАП" OR GRUZ_NAME = "ЭКОСКРАП 400+" OR GRUZ_NAME = "ЭКОСКРАП 500-800 мм" OR GRUZ_NAME = "ЭКОСКРАП 200+") AND TYP_EVENT="'.$typeevent.'"';

$array = DB2('GRUZ_NAME, NETTO, DOC, NOMER_TS, FIRMA_POL, DATETIME_CREATE','weighing',$vyborka.' and DATETIME_CREATE between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59" ORDER BY DATETIME_CREATE');
// По возрастанию:

$dategroup = [];
$p = 1;

foreach ($array as $n) {
	$key = '';
	$newDate = date("d.m.Y", strtotime($n['DATETIME_CREATE']));
	$key = array_search($newDate, $dategroup);
	if (array_key_exists($key, $dategroup)) {
 	
 	} else {
	 	$dategroup[] = $newDate;
 	}
}


foreach ($dategroup as $date) {
	
	// массив с грузами и с получателями
	$gruz = [];
	$firm = [];
	// формируем массив с грузами
	foreach ($array as $n) {
		$newDate = date("d.m.Y", strtotime($n['DATETIME_CREATE']));
		if ($newDate == $date) {
			$gruzname = '';
			$gruzname = array_search($n['GRUZ_NAME'], $gruz);
			if (array_key_exists($gruzname, $gruz)) {} else {
			 	$gruz[] = $n['GRUZ_NAME'];
		 	}
		} 
	}
	
	
	
	// выводим цикл с грузами
	foreach ($gruz as $g) {
		
		foreach ($array as $n) {
			$newDate = date("d.m.Y", strtotime($n['DATETIME_CREATE']));
			if ($newDate == $date) {
				$firmname = '';
				$firmname = array_search($n['FIRMA_POL'], $firm);
				if (array_key_exists($firmname, $firm)) {} else {
				 	$firm[] = $n['FIRMA_POL'];
			 	}
			} 
		}
		
		foreach($firm as $f) {
			$i = 1;
			if ($p != 1 and $s != 0) { 
				echo '</div></div>';
			}
			$s = 0;
			foreach ($array as $n) {
				$newDate = date("d.m.Y", strtotime($n['DATETIME_CREATE']));
				
				$arr = [];
				
				if ($newDate==$date and $n['GRUZ_NAME']==$g and $n['FIRMA_POL']==$f) {
				
				
				
				if($i == 1) {
					echo '<div class="card mb-3"><div class="card-header"><h5>'.$n['GRUZ_NAME'].'</h5></div><div class="card-body">';
				}
				
				echo '<p>'.$i.') '.$n['DATETIME_CREATE'].' - '.$n['GRUZ_NAME'].' - '.$n['FIRMA_POL'].' - '.$n['NETTO'].' ('.$p.')</p>';
				
				
				$arr[] = $n['DATETIME_CREATE'];
				var_dump($arr);
				$p++;
				$i++;
				$s++;
				}
		}
		}
		
	}
} echo '</div></div>';
} ?>