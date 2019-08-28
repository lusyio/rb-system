
    <link rel="stylesheet" href="/css/dop2.css?ver=4">
  <?php 
	 $newmoney = DBOnce('value','zenno','id=30');
	 $moneyupdate = DBOnce('dop','zenno','id=30');
	 $moneyupdate = date("d.m в H:i", strtotime($moneyupdate));
	 $sales = DBOnce('value','zenno','id=2');
	 $rashod = DBOnce('value','zenno','id=3');
	 ?>
	<div class="newmain">
		<div class="newmaintop">
				<h1><?php echo number_format($newmoney,0,'',' '); ?> руб.</h1>

<p class="mb-1">
<span class="text-success"><strong><i class="fas fa-arrow-up"></i></strong></span> <?php echo number_format($sales,0,'',' ');?>
<span class="text-danger ml-3"><strong><i class="fas fa-arrow-down"></i></strong></span> <?php echo number_format($rashod,0,'',' ');?>
</p>
<p class="mt-0">Данные по б/с обновлены <?php echo $moneyupdate;?></p>
		</div>
	</div>
    <div class="row">
	    <div class="col-md-7">
		    <div class="bankblock">
			    <div >
		    <?php
			    $bankclientcount = DBOnce('COUNT(*) as count','bank','date '.$bwmonth);
			    
			
				if ($bankclientcount > 0) {
					$sql = "SELECT *, SUM(summa), DATE(date) mydate FROM `bank` WHERE date ".$bwmonth." GROUP BY mydate DESC";
					
				    $sql = $pdo->prepare($sql);
					$sql->execute();
					$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
					foreach ($sql as $result) {
						
					   $newdate = $result['mydate'];
					   $arr = [
						  'января',
						  'февраля',
						  'марта',
						  'апреля',
						  'мая',
						  'июня',
						  'июля',
						  'августа',
						  'сентября',
						  'октября',
						  'ноября',
						  'декабря'
						];
						$day = date("j", strtotime($newdate));
						$month2 = (date("n", strtotime($newdate)))-1;
						echo '<p class="dateview">'.$day.' '.$arr[$month2].'</p><table class="table"><tbody>';
						
						
						$sql2 = "SELECT type, kontragent, summa, date FROM `bank` WHERE date ".$bwmonth." ORDER by date DESC";
						
						$sql2 = $pdo->prepare($sql2);
						$sql2->execute();
						$sql2 = $sql2->fetchAll(PDO::FETCH_BOTH);
							
						foreach ($sql2 as $result2) {
							
							$type = $result2['type'];
							$plus = array("Оплата от покупателя", "Перевод с другого счета");
							if (in_array($type, $plus)) {
								$znak = '+';
								$typeclass = 'text-success';
							} else {
								$znak = '-';
								$typeclass = 'text-danger';
							}
							
					   		echo '<tr><td><h5>'.$result2['kontragent'].'</h5><p>'.$result2['type'].'</p></td><td width="50%" class="text-right"><h5 class="'.$typeclass.'">'.$znak.number_format($result2['summa'],0,'',' ').' руб.</h5></td></tr>';
					   	}
					   		echo '</tbody></table>';
				    }
					} else {
						echo '<p class="text-center inside">Данных за период нет</p>';
					}
			?>
			    </div>
		    </div>
	    </div>
	    <div class="col-md-5">
		    <div class="bankblock">
					<p class="dateview">ТОП контрагентов</p>
					<?php 
						
					$kontrcount = DBOnce('COUNT(*) as count','bank','type="Оплата от покупателя" and date '.$bwmonth);
					
					if ($kontrcount > 0) {
					$sql = "SELECT *, SUM(summa), kontragent FROM `bank` WHERE type='Оплата от покупателя' and date between '".$month." 00:00:00' and '".$now." 23:59:59' GROUP BY kontragent ORDER BY SUM(summa) DESC";
					echo '<table class="table"><tbody>';
					$sql = $pdo->prepare($sql);
					$sql->execute();
					$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
					foreach ($sql as $result3) {
						
						echo '<tr><td>'.$result3['kontragent'].'</td><td class="text-right" width="50%">+'.number_format($result3['SUM(summa)'],0,'',' ').' руб.</td></tr>';
					}
					echo '</tbody></table>';
					} else {
						echo '<p class="text-center inside">Данных за период нет</p>';
					}
					?>
		    </div>
	    </div>
    </div>

