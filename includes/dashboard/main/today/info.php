<?php
	$sql = 'SELECT * FROM `brigada` WHERE report = "yes" order by id desc limit 1';
	 $sql = $pdo->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
	foreach ($sql as $result) {
		if ($result['plandone'] == 0) { $result['plandone'] = 'не выполнила план'; } else { $result['plandone'] = 'выполнила план'; }
		if ($result['daynight'] == 'day') { $result['daynight'] = 'дневная'; } else {$result['daynight'] = 'ночная';}
		$lastbrigad = 'предыдущая '.$result['daynight'].' смена '.$result['brigadir'].'а '.$result['plandone'].' ('.$result['plansmena'].'т.). Взяли '.numb($result['marten']+$result['lpk']+$result['don']+$result['oplmat']).'т. Комментарий по смене '.date("d.m", strtotime($result['datestart'])).': <i>"'.$result['comment'].'"</i>.';
	}
	if (!empty($brigadir)) {
		
		if ($plansmena <= $sumsmena) {
			$plantext = 'выполнила план в '.$plansmena.'т.';
		} else {
			$plantext = 'пока что не выполнила план в '.$plansmena.'т., взяв '.$sumsmena.'т.';
		} // если есть смена ?>
<p>Текущая смена <?=$brigadir?>а <?=$plantext?>, <?=$lastbrigad?> </p>
<?php } else {  // если нет текущей смены ?>
<p>В данный момент нет активной смены, <?=$lastbrigad?>
<?php } ?>



<?php
$prihod = DBOnce('SUM(value) AS sumvalue','kassa','type="Приход" and date '.$bwnow);
$rashod = DBOnce('SUM(value) AS sumvalue','kassa','type="Расход" and date '.$bwnow);
if ($prihod > 0 and $rashod > 0) {
	echo '<p>В кассу поступило '.numb($prihod).' руб., а на расходы ушло '.numb($rashod).' руб.</p>';
}
if ($prihod > 0 and $rashod == 0) {
	echo'<p>В кассу поступило '.numb($prihod).' руб.</p>';
}
if ($prihod == 0 and $rashod > 0) {
	echo '<p>На расходы потратили '.numb($rashod).' руб. из кассы.</p>';
}
?>



<?php
$bochka = DBOnce('toplivo','gsm','id=1');
$gsm = DBOnce('SUM(toplivo)','gsm','date = "'.$now.'"');
if ($gsm > 0) {
	$gsm2 = 'Заправили технику на '.numb($gsm).' литров. ';
} else {
	$gsm2 = 'Технику пока не заправляли. ';
}
?>
<p><?=$gsm2?>В бочке осталось <?=numb($bochka)?> литров.</p>