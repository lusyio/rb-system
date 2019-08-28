<?php
	$meta = DBOnce('avg(meta) AS meta_sum','brigada','datestart '.$bwmonth.' and report="yes"');
	$nowtime = date("G"); 
	if ($nowtime >=7 and $nowtime <19) { $datasmen = $now; $daynight = 'day'; $icon = '<i class="fas fa-sun text-warning"></i>'; }
	if ($nowtime >=19 and $nowtime <24) { $datasmen = $now; $daynight = 'night'; $icon = '<i class="fas fa-moon text-primary"></i>';}
	if ($nowtime >=0 and $nowtime <7) { $datasmen = $yesterday; $daynight = 'night'; $icon = '<i class="fas fa-moon text-primary"></i>';}
	$zennoupdate = DBOnce('dop','zenno','id=9');
	// Информация о текущей смене
	$smenanow = $pdo->prepare('SELECT * FROM `brigada` where datestart="'.$datasmen.'" and daynight = "'.$daynight.'"');
	$smenanow->execute();
	$smenanow = $smenanow->fetchAll(PDO::FETCH_BOTH);
	if (!empty($smenanow)) {
		
		
		foreach ($smenanow as $result) {
			$brigadir = $result['brigadir'];
			$plansmena = $result['plansmena'];
			$sumsmena = $result['marten'] + $result['lpk'] + $result['don'] + $result['oplmat'];
			$martensmena = $result['marten'];
			$lpksmena = $result['lpk'];
			$donsmena = $result['don'];
			$oplmat = $result['oplmat'];
			$datestartsmena = $result['datestart']; 
			$dateendsmena = $result['dateend'];
			$timestartsmena = $result['timestart'];
			$timeendsmena = $result['timeend'];
		}
		
		// Машины
		$auto = $pdoves->prepare('SELECT NETTO, DATETIME_CREATE, GRUZ_NAME FROM `weighing` where (GRUZ_NAME = "Карта выборки №1 МАРТЕН" OR GRUZ_NAME = "Карта выборки №2 ЛПК" OR GRUZ_NAME = "Оплаченный материал") and DATETIME_CREATE between "'.$datestartsmena.' '.$timestartsmena.'" and "'.$dateendsmena.' '.$timeendsmena.'" ORDER BY DATETIME_CREATE DESC');
		$auto->execute();
		$auto = $auto->fetchAll(PDO::FETCH_BOTH);
		
			// Сколько взяли побочки за месяц
		$zennopobo = DBOnce('value','zenno','id=1'); 
		// План побочки на месяц
		$planpoboch = DBOnce('value','zenno','id=7'); 
		// Осталось взять
		$prosplan = $planpoboch - $zennopobo;
		// Процент выполнения
		$prossmena = round(($sumsmena/$plansmena) * 100, 0);
		// Если взяли план
		if ($sumsmena >= $plansmena) { 
			$plantext = '. <strong>План выполнен <i class="fas fa-check"></i></strong>';
			$prossmena = '100';
		}
		// Если пока не взяли
		if ($sumsmena < $plansmena) {
			$plantext = '. В смену нужно взять минимум '.$plansmena.'т.';
		}?>
		 
		 <div class="info-block nonborder" >
	<div class="inside">
		<h1><?=$sumsmena.' т.'?></h1>
		<p><?=$icon.' '.$brigadir.$plantext?></p>
		<div class="progress">
			<div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $prossmena; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $sumsmena; ?> / <?php echo $plansmena;?></div>
		</div>
		<table class="table table-hover mt-3">
			<tbody>
				<?php 
					if($martensmena > 0) { echo'<tr><td width="10px"><span id="h1"></span></td><td><strong>Мартен</strong></td><td>'.$martensmena.' т.</td></tr>';}
					if($lpksmena > 0) { echo' <tr><td width="10px"><span id="h2"></span></td><td><strong>ЛПК</strong></td><td>'.$lpksmena.' т.</td></tr>';}
					if($donsmena > 0) { echo'<tr><td width="10px"><span id="h3"></span></td><td><strong>Донышки</strong></td><td>'.$donsmena.' т.</td></tr>';}
					if($oplmat > 0) { echo'<tr><td width="10px"><span id="h4"></span></td><td><strong>Опл.материал</strong></td><td>'.$oplmat.' т.</td></tr>';}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
		 
		 
		 
	} else {
		echo '<div class="info-block nonborder" ><div class="inside text-center"><p>В данный момент нет активных смен</p><p class="small text-muted">Информация обновлена '.$zennoupdate.'</p></div></div>';
	}
	
	
	
	
	
	
	
	
?>
 
<?php
	if (!empty($auto)) {
		echo '<div class="info-block" ><div class="inside"><div class="table-responsive"> <table class="table table-hover table-striped"><thead><tr><th scope="col">Время</th><th scope="col">Груз</th><th scope="col">Вес</th></tr></thead><tbody>';
		foreach ($auto as $result) {
				$ves = number_format($result['NETTO']/1000, 2, ',', '');
			    $newDate2 = date("H:i", strtotime($result['DATETIME_CREATE']));
			    if ($result['GRUZ_NAME'] == 'Карта выборки №1 МАРТЕН') {
				    $gruz = 'МАРТЕН';
			    }
			    if ($result['GRUZ_NAME'] == 'Карта выборки №2 ЛПК') {
				    $gruz = 'ЛПК';
			    } 
			    if ($result['GRUZ_NAME'] == 'Оплаченный материал') {
				    $gruz = 'Опл.материал';
			    }
			    echo '<tr><td><strong>'.$newDate2.'</strong></td><td><strong>'.$gruz.'</strong></td><td>'.$ves.' т.</td></tr>';
		}
		echo '</tbody></table></div><p class="small">Обновлено: '.$zennoupdate.'</p></div></div>';
	}
	
?>


<div class="info-block nonborder"><div class="stan">Балл за месяц <strong><?php echo number_format($meta,1,'.','');?></strong><a href="/report/"><button class="btn btn-info podrobno"><i class="fas fa-arrow-right"></i></button></a></div> </div> 