<?php
	$otrgyz = DBOnce2('COUNT(*) as count','weighing','(GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	if ($otrgyz>0) {
	
	$sh05sum = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Песок шлаковый 0-5 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	$sh520sum = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 5-20 мм" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	$sh020sum = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 0-20 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	$sh2040sum = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-40 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	$sh2070sum = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-70 мм." and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	
	
	
	$allshebtoday = DBOnce2('SUM(NETTO)','weighing','(GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	
	
		
?>

<div class="info-block gsm-DATETIME_CREATE">
	<div class="inside">
		<h4>Отгрузки за сегодня</h4>
		<div class="table-responsive">
<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th scope="col"></th>
			<?php if ($sh05sum > 0) { ?> <th scope="col">0-5</th> <?php } ?>
			<?php if ($sh520sum > 0) { ?> <th scope="col">5-20</th> <?php } ?>
			<?php if ($sh020sum > 0) { ?> <th scope="col">0-20</th> <?php } ?>
			<?php if ($sh2040sum > 0) { ?> <th scope="col">20-40</th> <?php } ?>
			<?php if ($sh2070sum > 0) { ?> <th scope="col">20-70</th> <?php } ?>
		</tr>
	</thead>
	<tbody id="widj-otgryz">
<?php  $sql = 'SELECT FIRMA_POL FROM weighing WHERE (GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow.' GROUP BY FIRMA_POL';
		    $sql = $pdoves->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result) {
				
				$sh05 = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Песок шлаковый 0-5 мм" and FIRMA_POL="'.$result['FIRMA_POL'].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
				$sh520 = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 5-20 мм" and FIRMA_POL="'.$result['FIRMA_POL'].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
				$sh020 = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 0-20 мм." and FIRMA_POL="'.$result['FIRMA_POL'].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
				$sh2040 = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-40 мм." and FIRMA_POL="'.$result['FIRMA_POL'].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
				$sh2070 = DBOnce2('SUM(NETTO)','weighing','GRUZ_NAME="Щебень 20-70 мм." and FIRMA_POL="'.$result['FIRMA_POL'].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
				
				$polych = $result['FIRMA_POL'];
				
				if ($polych == 'ОООСтройТехМеханизация') { $polych = 'СТМ'; }
				if ($polych == 'Администрация городского округа г. Выкса') { $polych = 'Администрация'; }
				if ($polych == 'ЗАО ПМК ВЫКСУНСКАЯ') { $polych = 'ПМК'; }
				if ($polych == 'ООО Асфальтный завод Сарова') { $polych = 'Саров'; }
				if ($polych == 'ОАО «Рязаньавтодор»') { $polych = 'Рязаньавтодор'; }
				if ($polych == 'Фьючерс. Красовский') { $polych = 'Красовский (ф)'; }
				if ($polych == 'сотрудникам РУБЕЖА-В') { $polych = 'Сотрудникам'; }
				if ($polych == 'ООО «Птицекомплекс ВыксОВО»') { $polych = 'Птицекомплекс'; }
				if ($polych == 'ОООАРСЕНАЛ') { $polych = 'АРСЕНАЛ'; }
				if ($polych == 'ООО Грин Строй') { $polych = 'Грин Строй';  }
				if ($polych == 'ООО Дана') { $polych = 'Дана'; }
				if ($polych == 'ОООУКРусККом') { $polych = 'УКРусККом'; }
				if ($polych == 'ООО СканГруз') { $polych = 'СканГруз'; }
				if ($polych == 'ООО СтимСтрой') { $polych = 'СтимСтрой'; }
				if ($polych == 'ОООДСУЕрмишинский') { $polych = 'ДСУЕрмиш'; }
				if ($polych == 'ЗАО «Управление Механизированных работ - 10 »') { $polych = 'УМР-10'; }
				
				echo '<tr><td>'.$polych.'</td>'; ?>
				
			   <?php if ($sh05sum > 0) { ?> <th scope="col"><?=numb($sh05/1000)?></th> <?php } ?>
			   <?php if ($sh520sum > 0) { ?> <th scope="col"><?=numb($sh520/1000)?></th> <?php } ?>
			   <?php if ($sh020sum > 0) { ?> <th scope="col"><?=numb($sh020/1000)?></th> <?php } ?>
			   <?php if ($sh2040sum > 0) { ?> <th scope="col"><?=numb($sh2040/1000)?></th> <?php } ?>
			   <?php if ($sh2070sum > 0) { ?> <th scope="col"><?=numb($sh2070/1000)?></th> <?php } ?>
				
		<?php	echo '</tr>';
		    }
				    
			
					    
			echo '<th scope="col"></th>';
			?>
			<?php if ($sh05sum > 0) { ?> <th scope="col"><?=numb($sh05sum/1000)?></th> <?php } ?>
			<?php if ($sh520sum > 0) { ?> <th scope="col"><?=numb($sh520sum/1000)?></th> <?php } ?>
			<?php if ($sh020sum > 0) { ?> <th scope="col"><?=numb($sh020sum/1000)?></th> <?php } ?>
			<?php if ($sh2040sum > 0) { ?> <th scope="col"><?=numb($sh2040sum/1000)?></th> <?php } ?>
			<?php if ($sh2070sum > 0) { ?> <th scope="col"><?=numb($sh2070sum/1000)?></th> <?php } ?>		      		  
	</tbody>
</table>
		</div>
	</div>
</div>
<?php }?>