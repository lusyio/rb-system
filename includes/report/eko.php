<?php
	
	if ($iduser == 1 or $iduser == 2 or $iduser == 4) {
	
	// ЭКО
	$typeevent = 'Реализация (отгрузка покупателю)';
	$datestart2 = date("d.m.Y", strtotime($datestart));
	$dateend2 = date("d.m.Y", strtotime($dateend));
	
	$vyborka = '(GRUZ_NAME = "ЭКОСКРАП" OR GRUZ_NAME = "ЭКОСКРАП 400+" OR GRUZ_NAME = "ЭКОСКРАП 500-800 мм" OR GRUZ_NAME = "ЭКОСКРАП 200+") AND TYP_EVENT="'.$typeevent.'"';
	
	
	$all = DBOnce2('SUM(NETTO)','weighing',$vyborka.' and DATETIME_CREATE between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"');
	
	if ($all > 0) {
		
	 $sql = "SELECT GRUZ_NAME, NETTO, DOC, NOMER_TS, FIRMA_POL, DATETIME_CREATE FROM weighing WHERE ".$vyborka." and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' ORDER BY DATETIME_CREATE";
			 
		echo '
		<table class="table table-hover table-striped">
				  <thead>
				    <tr>
				      <th scope="col">Дата</th>
				      <th scope="col">Номер ТС</th>
				      <th scope="col">Груз</th>
				      <th scope="col">Получатель</th>
				      <th scope="col">Нетто</th>';
				echo '    </tr>
				  </thead>
				  <tbody>';
				    $sql = $pdoves->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {
					  
					$newDate2 = date("d.m.Y H:i", strtotime($result['DATETIME_CREATE']));	  
					$FIRMA_POL = $result['FIRMA_POL'];
					$ves = $result['NETTO']/1000;
					if ($result['NETTO'] > 0) {
					echo '<tr><td>'.$newDate2.'</td><td>'.$result['NOMER_TS'].'</td><td>'.$result['GRUZ_NAME'].'</td><td>'.$FIRMA_POL.'</td><td>'.$ves.' т.</td></tr>'; } else {}
					  
				    }
				    
				  $all2 = $all/1000;
				  echo '<tr><td> </td><td> </td><td></td><td><strong>Всего:</strong></td><td><strong>'.$all2.' т.</strong></td></tr>
			</tbody>
		</table>';
		} else  { echo '<p>Нет данных за период</p>';
		 } 
	} else {
		echo '<p>Ошибка</p>';
	}	 
			// запись в лог
		$period1 = date("d.m", strtotime($datestart));
		$period2 = date("d.m", strtotime($dateend));
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = 'Сформировал отчет по эко. Период: '.$period1.' - '.$period2;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));		 
?>