<div class="info-block gsm-date">
	<div class="inside">
		<div class="row text-center">
			<div class="col-xs-3 toplsut rightborder">
				<h4><?=DBOnce('toplivo','gsm','id=1');?><span>л.</span></h4>
				<p class="text-warning"><strong>Остаток в бочке</strong></p>
			</div>
		<?php
			// 5 дней гсм
			$gsm = $pdo->prepare('SELECT date, SUM(toplivo) AS user_sum FROM `gsm` GROUP BY date DESC LIMIT 3');
			$gsm->execute();
			$gsm = $gsm->fetchAll(PDO::FETCH_BOTH);
		    foreach ($gsm as $result) {
			    $newDate = date("d.m", strtotime($result['date']));
			    echo '<div class="col-xs-3 toplsut"><h4>'.$result['user_sum'].'<span>л.</span></h4><p>'.$newDate.'</p></div>';
		    }
		?>
		</div>
	</div>
</div>

<div class="info-block">
	<div class="inside">
		<table class="table table-hover">
		  	<thead>
			    <tr>
			      <th scope="col">Техника</th>
			      <th scope="col">Дата</th>
			      <th scope="col">Топливо</th>
			    </tr>
		  	</thead>
		  	<tbody>
		<?php
		    $sql = 'SELECT date, id, tech, date, toplivo  FROM gsm WHERE id > 2 ORDER BY date DESC LIMIT 10';
		    $sql = $pdo->prepare($sql);
$sql->execute();
$sql = $sql->fetchAll(PDO::FETCH_BOTH);
foreach ($sql as $result) {
			    $newDate2 = date("d.m", strtotime($result['date']));
		        echo '<tr><td>'.$result['tech'].'</td><td>'.$newDate2.'</td><td>'.$result['toplivo'].' л.</td></tr>';
		    }
		?>
			</tbody>
		</table>
	</div>
</div>