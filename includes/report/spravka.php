<?php
$typeevent = 'Реализация (отгрузка покупателю)';
	
	$i = 0;
	$datestart2 = date("d.m.Y", strtotime($datestart));
	$dateend2 = date("d.m.Y", strtotime($dateend));
	$sql = "SELECT GRUZ_NAME, VESYNAME, NETTO, BRUTTO, NOMER_TS, TARA, DATETIME_CREATE FROM weighing WHERE TYP_EVENT='".$typeevent."' and VESYNAME = 'Весы 3' and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' ORDER BY DATETIME_CREATE";
		echo '<div class="zag"><h4 class="float-left">Справка о взвешивании вагонов</h4>
<form method="post" action="/download.php" class="float-right" target="_blank">
				    <input type="date" class="form-control hidden" name="datestart" value="'.$datestart.'">
				    <input type="date" class="form-control hidden" name="dateend" value="'.$dateend.'">
				    <input type="text" class="form-control hidden" name="idreport2" value="1">
			    <button type="submit" class="btn btn-primary">Скачать EXCEL справку <i class="fas fa-download"></i></button>
			    </form><div class="clear"></div></div>';
	echo	'<table class="table table-hover" style="margin-top: 20px;">';
	include 'spravka-table.php';
?>