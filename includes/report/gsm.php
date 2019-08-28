<?php
// ГСМ
		
		$period1 = date("d.m", strtotime($datestart));
		$period2 = date("d.m", strtotime($dateend));
		echo '
		<h4>Расход ГСМ '.$period1.' - '.$period2.'.</h4>';
		
		$sql = "SELECT date, id, tech, date, SUM(toplivo)  FROM gsm WHERE id > 2 and date between '".$datestart."' and '".$dateend."' GROUP BY tech DESC";
		$sql = $pdo->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		foreach ($sql as $result) {
			echo '<div class="float-left gsminfo"><p><strong>'.$result['tech'].'</strong></p><p>'.$result['SUM(toplivo)'].' л.</p></div>';
		}
		
echo '<div class="clear"></div><table class="table table-hover mt-3">
		  	<thead>
			    <tr>
			      <th scope="col">Техника</th>
			      <th scope="col">Дата</th>
			      <th scope="col">Топливо</th>
			    </tr>
		  	</thead>
		  	<tbody>';
			    $sql = "SELECT date, id, tech, date, toplivo  FROM gsm WHERE id > 2 and date between '".$datestart."' and '".$dateend."' ORDER BY date DESC";
				$sql = $pdo->prepare($sql);
				$sql->execute();
				$sql = $sql->fetchAll(PDO::FETCH_BOTH);
				foreach ($sql as $result) {
				    $newDate2 = date("d.m", strtotime($result['date']));
					echo '<tr><td>'.$result['tech'].'</td><td>'.$newDate2.'</td><td>'.$result['toplivo'].' л.</td></tr>';
			        }
			   echo '
			</tbody>
		</table>';
		
					// запись в лог
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = 'Сформировал отчет по ГСМ. Период: '.$period1.' - '.$period2;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
?>