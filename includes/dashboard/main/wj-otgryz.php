<?php 
	 // Щебень
	 $sh05sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Песок шлаковый 0-5 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	 $sh520sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 5-20 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	 $sh020sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 0-20 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	$sh2040sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-40 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	$sh2070sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-70 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;	
	$allshebsum = $sh05sum + $sh520sum + $sh020sum + $sh2040sum + $sh2070sum;
	// СКРАП
	$scrap1550sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="СКРАП 15-50" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	$scrap50400sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="СКРАП 50-400 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	$scrap400sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="СКРАП 400 плюс" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	$scrap500800sum = (DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="СКРАП 500-800 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwmonth))/1000;
	
	if ($iduser != 20) {
		$allscrap = $scrap1550sum + $scrap50400sum + $scrap400sum + $scrap500800sum;
	} else {
		$allscrap = $scrap50400sum + $scrap400sum + $scrap500800sum;
	}
	$allotgryz = $allshebsum + $allscrap;
	// Кол-во машин вагонов
	$vagon = DBOnce('dop','zenno','id=2');
	$cars = DBOnce('dop','zenno','id=3');
?>
<div class="newmaintop">
<h1><?php echo numb($allotgryz);?> т.</h1>

<p class="mb-1">
	<span class="text-info"><strong>Щ:</strong></span> <?php echo numb($allshebsum);?> т.
	<span class="text-warning ml-3"><strong>С:</strong></span> <?php echo numb($allscrap);?> т.
</p>
<p class="mt-0">Отгрузки за месяц</p>
<div class="indi-l">
	<?php echo $cars;?> машин
</div>
<div class="indi-r">
	<?php echo $vagon;?> вагонов
</div>
</div>

<table class="table tablesmen">
<tbody>
	<tr><td width="10px"><span id="h1"></span></td><td><strong>Щебень 0-5</strong></td><td><?php echo numb($sh05sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h1"></span></td><td><strong>Щебень 5-20</strong></td><td><?php echo numb($sh520sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h1"></span></td><td><strong>Щебень 0-20</strong></td><td><?php echo numb($sh020sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h2"></span></td><td><strong>Щебень 20-40</strong></td><td><?php echo numb($sh2040sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h3"></span></td><td><strong>Щебень 20-70</strong></td><td><?php echo numb($sh2070sum);?> т.</td></tr>
	<?php if ($iduser != 20) { ?>
	<tr><td width="10px"><span id="h4"></span></td><td><strong>СКРАП 15-50</strong></td><td><?php echo numb($scrap1550sum);?> т.</td></tr>
	<?php } ?>
	<tr><td width="10px"><span id="h5"></span></td><td><strong>СКРАП 50-400</strong></td><td><?php echo numb($scrap50400sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h6"></span></td><td><strong>СКРАП 400+</strong></td><td><?php echo numb($scrap400sum);?> т.</td></tr>
	<tr><td width="10px"><span id="h7"></span></td><td><strong>СКРАП 500-800</strong></td><td><?php echo numb($scrap500800sum);?> т.</td></tr>
</tbody>
</table>