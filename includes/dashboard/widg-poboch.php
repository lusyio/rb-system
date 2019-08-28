<?php
	// Сколько взяли побочки за месяц
	$zennopobo = DBOnce('value','zenno','id=1'); 
	// План побочки на месяц
	$planpoboch = DBOnce('value','zenno','id=7'); 
	// Осталось взять
	$prosplan = $planpoboch - $zennopobo;
	// Процент выполнения
	$prossmena = round(($zennopobo/$planpoboch) * 100, 0);
	
	$martenmonth = DBOnce('value','zenno','id=10'); 
	$lpkmonth = DBOnce('value','zenno','id=11');
	$donmonth = DBOnce('value','zenno','id=12');
	$oplmat = DBOnce('value','zenno','id=14');
?>
<div class="info-block nonborder" >
	<div class="inside">
		<h1><?=numb($zennopobo).' т.'?></h1>
		<p>Побочка за месяц. План <?=numb($planpoboch)?>т.</p>
		<div class="progress">
			<div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $prossmena; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo numb($zennopobo); ?> / <?php echo numb($planpoboch);?></div>
		</div>
		<table class="table table-hover mt-3">
			<tbody>
				<?php 
					if($martenmonth > 0) { echo'<tr><td width="10px"><span id="h1"></span></td><td><strong>Мартен</strong></td><td>'.numb($martenmonth).' т.</td></tr>';}
					if($lpkmonth > 0) { echo' <tr><td width="10px"><span id="h2"></span></td><td><strong>ЛПК</strong></td><td>'.numb($lpkmonth).' т.</td></tr>';}
					if($donmonth > 0) { echo'<tr><td width="10px"><span id="h3"></span></td><td><strong>Донышки</strong></td><td>'.numb($donmonth).' т.</td></tr>';}
					if($oplmat > 0) { echo'<tr><td width="10px"><span id="h4"></span></td><td><strong>Опл.материал</strong></td><td>'.numb($oplmat).' т.</td></tr>';}
				?>
			</tbody>
		</table>
	</div>
</div>