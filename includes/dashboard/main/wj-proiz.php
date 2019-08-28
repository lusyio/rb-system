<?php
$marten = numb(DBOnce('value','zenno','id=10')); 
$lpk = numb(DBOnce('value','zenno','id=11'));
$don = numb(DBOnce('value','zenno','id=12'));
$opl = numb(DBOnce('value','zenno','id=14'));
$kopr = numb((DBOnce2('SUM(NETTO)','weighing','(FIRMA_POL="СкладК" or FIRMA_POL="СкладК1" or FIRMA_POL="СкладК2") and DATETIME_CREATE '.$bwmonth))/1000);
$rezr = numb((DBOnce2('SUM(NETTO)','weighing','(FIRMA_POL="СкладР" or FIRMA_POL="СкладР1" or FIRMA_POL="СкладР2") and DATETIME_CREATE '.$bwmonth))/1000);
?>
<?php
// оценка
$meta = DBOnce('avg(meta) AS meta_sum','brigada','datestart '.$bwmonth.' and report="yes"');
// данные по текущей смене
$nowtime = date("G"); 
$nowday = date("d"); 
if ($nowtime >=7 and $nowtime <19) { $datasmen = $now; $daynight = 'day'; $icon = '<i class="fas fa-sun text-warning"></i>'; }
if ($nowtime >=19 and $nowtime <24) { $datasmen = $now; $daynight = 'night'; $icon = '<i class="fas fa-moon text-primary"></i>';}
if ($nowtime >=0 and $nowtime <7) { $datasmen = $yesterday; $daynight = 'night'; $icon = '<i class="fas fa-moon text-primary"></i>';}	
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
if ($plansmena > $sumsmena) {
		$plansmenatext = 'План: '.$plansmena.' т.'; 
	} else {
		$plansmenatext = 'План ('.$plansmena.'т.) выполнен <i class="fas fa-check"></i>';
	}
// когда брали побочку
$datepob = DBOnce2('DATETIME_CREATE','weighing','GRUZ_NAME = "Карта выборки №1 МАРТЕН" OR GRUZ_NAME ="Карта выборки №2 ЛПК" OR GRUZ_NAME ="Карта выборки №3 ДОНЫШКИ" OR GRUZ_NAME ="Оплаченный материал" ORDER BY DATETIME_CREATE DESC');
$dateviborka = date("d", strtotime($datepob));
if ($nowday == $dateviborka) {
	$datepob = date("H:i", strtotime($datepob));
} else {
	$datepob = date("d.m в H:i", strtotime($datepob));
}



?>
<div class="newmaintop">
	<h1><span class="smenanow"><?=numb($sumsmena)?></span> т.</h1>
	<div class="pobochsmen">
		<p class="mb-1 pobochsmen">
			<span class="text-danger"><strong>М:</strong></span> <?php echo $martensmena;?>
			<span class="text-warning ml-3"><strong>Л:</strong></span> <?php echo $lpksmena;?>
			<span class="text-success ml-3"><strong>Д:</strong></span> <?php echo $donsmena;?>
			<span class="text-primary ml-3"><strong>О:</strong></span> <?php echo $oplmat;?>
		</p>
		<p class="mt-0">Побочку брали в <?php echo $datepob;?></p>
		<span class="plansmena">
			<div class="indi-l">
				<span class="brigadir"><?php echo $icon.' '.$brigadir;?></span><br>
				<span class="plansmena"><?php echo $plansmenatext;?></span><br>
				<span class="porssmen">Балл: <?=number_format($meta,1,'.','')?></span>
			</div>
			<div class="indi-r">
				<span class="porssmen">К: <?=$kopr?></span><br>
				<span class="porssmen">Р: <?=$rezr?></span>
			</div>
		</span>
	</div>
</div>	
 <?php
	 } else {
	echo '<div class="pobochsmen">
	<span class="plansmena">
			<div class="indi-l">
			</div>
			<div class="indi-r">
				<span class="porssmen">К: '.$kopr.'</span><br>
				<span class="porssmen">Р: '.$rezr.'</span>
			</div>
		</span></div>
	<p class="text-center mt-5 mb-5 pb-5">Нет активной смены в данный момент</p>';
}
	// Сколько взяли побочки за месяц
	$zennopobo = DBOnce('value','zenno','id=1'); 
	// План побочки на месяц
	$planpoboch = DBOnce('value','zenno','id=7'); 
	// Осталось взять
	$prosplan = $planpoboch - $zennopobo;
// Процент выполнения
	$prossmena = round(($zennopobo/$planpoboch) * 100, 0);
	if ($prossmena > 90) {
		$color = 'green';
	} else {
		$color = 'orange';
	}
?>

<div class="inner-content text-center">
	<div class="c100 p<?=$prossmena?> dark big center <?=$color?>">
    <span><?=$prossmena?>%</span>
    <div class="slice"><div class="bar"></div><div class="fill"></div></div>
 </div>
 <p class="mb-1 mt-3 pobochsmen">
	<span><span class="text-danger"><strong>М:</strong></span> <?=$marten?></span>
	<span><span class="text-warning ml-3"><strong>Л:</strong></span> <?php echo $lpk;?></span>
	<span><span class="text-success ml-3"><strong>Д:</strong></span> <?php echo $don;?></span>
	<span><span class="text-primary ml-3"><strong>О:</strong></span> <?php echo $opl;?></span>
</p>
<p class="mt-3">Взяли <?=numb($zennopobo)?> т. побочки в этом<br>месяце. <?php if($prosplan<0) { ?> План выполнен.<?php } else { ?> Осталось <?=numb($prosplan)?> т. <?php } ?></p>
</div>
