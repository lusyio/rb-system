<?php 
$newmoney = DBOnce('value','zenno','id=30');
$moneyupdate = DBOnce('dop','zenno','id=30');
$moneyupdate = date("d.m в H:i", strtotime($moneyupdate));  
$kassa = DBOnce('value','kassa','id=1');
$kassavlada = DBOnce('value','kassavlada','id=1');
// доходы
$saleskassa = DBOnce('SUM(value) AS sumvalue','kassa','type="Приход" and date '.$bwmonth);
$sales = DBOnce('dop','zenno','id=2');
// расходы
$rashod = DBOnce('value','zenno','id=3');
$rashodkassa = DBOnce('SUM(value) AS sumvalue','kassa','type="Расход" and date '.$bwmonth);
?>


<div class="newmaintop">
	<h1><?php echo number_format($kassa,0,'',' '); ?> руб.</h1>

<p class="mb-1">
<span class="text-success"><strong><i class="fas fa-arrow-up"></i></strong></span> <?php echo number_format($saleskassa,0,'',' ');?>
<span class="text-danger ml-3"><strong><i class="fas fa-arrow-down"></i></strong></span> <?php echo number_format($rashodkassa,0,'',' ');?>
</p>
<p class="mt-0">Касса Евдокимова</p>
<div class="indi-l">
	 <strong><?php echo number_format($newmoney,0,'',' ');?> руб.</strong><br>
	 Б/с (<?php echo $moneyupdate;?>)
</div>
<div class="indi-r">
	 <strong><?php echo number_format($kassavlada,0,'',' ').' руб.'; ?></strong><br>
	 Влада
 </div>
</div>
<div id="kassadima">
<?php
$sql = "SELECT type, value, what, date FROM kassa WHERE id > 2 ORDER BY date DESC LIMIT 50";
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
echo '<p>'.$summa.': '.$result['what'].' '.$newDate2.'</p>';
			    }
?>
</div>