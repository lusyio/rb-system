<?php
// Касса
		$kassa = 'kassavlada';
		
		$rashod = DBOnce('SUM(value) AS sumvalue',$kassa,'type="Расход" and date between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"'); 
		$prihod = DBOnce('SUM(value) AS sumvalue',$kassa,'type="Приход" and date between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"'); 
		
	
		$allcash = number_format($prihod-$rashod,0,'',' ');
		$period1 = date("d.m", strtotime($datestart));
		$period2 = date("d.m", strtotime($dateend));
		
			// запись в лог
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = 'Сформировал отчет по кассе (Влада). Период: '.$period1.' - '.$period2;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
		
		echo '
		<h4>'.$allcash.' руб.</h4>
		<p>Общий итог по кассе Влады за период '.$period1.' - '.$period2.'. Расход: '.numb($rashod).' руб. Приход: '.numb($prihod).' руб.</p>
		<table class="table table-hover">
		  	<thead>
			    <tr>
			    	<th>Наименование</th>
					<th>Сумма</th>
					<th>Дата</th>
			    </tr>
		  	</thead>
		  	<tbody>';
			    $sql = "SELECT type, value, what, date FROM ".$kassa." WHERE id > 2 and date between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' ORDER BY date DESC";
			    $sql = $pdo->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {
				    $newDate2 = date("d.m", strtotime($result['date']));
				    $value = number_format($result['value'],0,'',' ');
				    
				    if ($result['type'] == 'Приход') {
						$summa = '<span class="text-success"><strong>+'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
					}
					
					if ($result['type'] == 'Расход') {
						$summa = '<span class="text-danger"><strong>-'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
					}
				    
				    
			        echo '<tr><td>'.$result['what'].'</td><td style="min-width:170px">'.$summa.'</td><td>'.$newDate2.'</td></tr>';
			        }
			   echo '
			</tbody>
		</table>';
?>