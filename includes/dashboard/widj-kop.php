<?php
	$arr = [
				  'январь',
				  'февраль',
				  'март',
				  'апрель',
				  'май',
				  'июнь',
				  'июль',
				  'август',
				  'сентябрь',
				  'октябрь',
				  'ноябрь',
				  'декабрь'
				];
	$monthold2 = (date("n", strtotime($datetime)))-1;
	$monthold = (date("m", strtotime("-1 months")))-1;
	$monthold3 = date("Y-m-01", strtotime("-1 months"));
	$monthold4 = date("Y-m-31", strtotime("-1 months"));
?>
<div class="info-block">
	<div class="inside">
	<?php
		$rezr = (DBOnce2('SUM(NETTO)','weighing','(FIRMA_POL="СкладК" or FIRMA_POL="СкладК1" or FIRMA_POL="СкладК2") and DATETIME_CREATE '.$bwmonth))/1000;
		echo '<h2 class="pt-3">'.$rezr.' т.</h2>';
		echo '<p>Поступило за '.$arr[$monthold2].' на СкладК:</p>';
	?>
	<div class="table-responsive">
		<table class="table table-hover table-striped">
			<tbody>
		<?php
			// Копер
			$kop = $pdoves->prepare('SELECT NETTO, DATETIME_CREATE FROM `weighing` where(FIRMA_POL="СкладК" or FIRMA_POL="СкладК1" or FIRMA_POL="СкладК2") and DATETIME_CREATE '.$bwmonth.' ORDER BY DATETIME_CREATE DESC');
			$kop->execute();
			$kop = $kop->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($kop as $result) {
			   
			    $ves = number_format($result['NETTO']/1000, 2, ',', '');
			    $newDate2 = date("d.m в H:i", strtotime($result['DATETIME_CREATE']));
			    echo '<tr><td><p class="mb-0"><strong>'.$ves.' т.</strong></p></td><td><p class="mb-0">'.$newDate2.'</p></td></tr>';
		    }

		?>
			</tbody>
		</table>
	</div>

	<a class="btn btn-success hook in" data-toggle="collapse" href=".hook" aria-expanded="false" aria-controls="collapseExample">Данные за <?php echo $arr[$monthold];?> 	<i class="fas fa-arrow-down"></i></a> 
	<div class="collapse hook">
		<div class="well">
			<?php
				$rezr = (DBOnce2('SUM(NETTO)','weighing','(FIRMA_POL="СкладК" or FIRMA_POL="СкладК1" or FIRMA_POL="СкладК2") and DATETIME_CREATE  between "'.$monthold3.' 00:00:00" and "'.$monthold4.' 23:23:59"'))/1000;
		echo '<h2 class="pt-3">'.$rezr.' т.</h2>';
		echo '<p>Поступило за '.$arr[$monthold].' на СкладК:</p>';
		?>
  			<div class="table-responsive">
<table class="table table-hover table-striped">
				  <tbody>
  	
  	<?php
	  	// Копер прошлый месяц
			$kop = $pdoves->prepare('SELECT NETTO, DATETIME_CREATE FROM `weighing` where(FIRMA_POL="СкладК" or FIRMA_POL="СкладК1" or FIRMA_POL="СкладК2") and DATETIME_CREATE between "'.$monthold3.' 00:00:00" and "'.$monthold4.' 23:23:59" ORDER BY DATETIME_CREATE DESC');
			$kop->execute();
			$kop = $kop->fetchAll(PDO::FETCH_BOTH);
			
			
			foreach ($kop as $result) {
			   
			    $ves = number_format($result['NETTO']/1000, 2, ',', '');
			    $newDate2 = date("d.m в H:i", strtotime($result['DATETIME_CREATE']));
			    echo '<tr><td><p class="mb-0"><strong>'.$ves.' т.</strong></p></td><td><p class="mb-0">'.$newDate2.'</p></td></tr>';
		    }
	  	

?>
				  </tbody>
</table>
		</div>
  	
  	

  </div>
</div>
	</div>
</div>