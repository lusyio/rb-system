<?php 	
	include 'bdcon.php'; 
		
	include_once('includes/SmsaeroApiV2.class.php');
    use SmsaeroApiV2\SmsaeroApiV2;
    $smsaero_api = new SmsaeroApiV2('mr-kelevras@yandex.ru', 'sPoFY1rcyAd6DbID44ZM4xstSUPX', 'Rubezh'); // api_key из личного кабинета
    
  	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
  	ini_set('display_startup_errors', 1);
	$datemonth = 'DATETIME_CREATE between "2017-05-01 00:00:00" and "'.$now.' 23:59:59"';
	$pob = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Побочный продукт сталеплавильного производства' and ".$datemonth))/1000);
	$marten = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №1 МАРТЕН' and ".$datemonth))/1000);
	$lpk = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №2 ЛПК' and ".$datemonth))/1000);
	$don = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №3 ДОНЫШКИ' and ".$datemonth))/1000);
	
	$sum = $pob + $marten + $lpk + $don;
	if ($sum >= 1000000) {
		$c = DBOnce('dop','zenno','id=10');
		echo $c;
		if ($c == 0) {
				$updatemonth = 'UPDATE zenno SET dop = "1" WHERE id = "10"';
				$updatemonth = $pdo->prepare($updatemonth);
				$updatemonth->execute();
				var_dump($smsaero_api->send(['79266556988','79265861133'],'Поздравляем! Переработано 1.000.000 тонн побочного продукта!', 'DIRECT')); // Отправка сообщений
		}
	} else {
		echo 'Пока не достигли миллиона :-(';
	}
	
	?>
	<hr>
	<p>Побочный - <?=$pob?> т.</p>
	<p>Мартен - <?=$marten?> т.</p>
	<p>ЛПК - <?=$lpk?> т.</p>
	<p>Донышки - <?=$don?> т.</p>
	<p>Всего - <?=$sum?> т.</p>

