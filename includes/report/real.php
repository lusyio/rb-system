<?php
	
	$period1 = date("d.m", strtotime($datestart));
	$period2 = date("d.m", strtotime($dateend));
	$id = 0;
	if ($iduser == 20) {
		$anti = " and GRUZ_NAME != 'СКРАП 15-50' ";
	} else {
		$anti = "";
	}
	$vyborka = "TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' and GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм'".$anti." and GRUZ_NAME != ''";
	
	$sql = "SELECT GRUZ_NAME FROM weighing WHERE ".$vyborka." GROUP BY GRUZ_NAME";
 
	$sql = $pdoves->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
	foreach ($sql as $result) {
		$id = $id + 1; 
		
		$gruz[$id] = $result['GRUZ_NAME'];
	}

		
	echo '<h5>Отгрузки с '.$period1.' 00:00 по '.$period2.' 23:59</h5>
	<div class="table-responsive">
		<table class="table table-hover table-bordered table-sm table-striped mt-3">
				  <thead>
				    <tr>
				      <th scope="col">Организации</th>';
				      for ($i = 1; $i <= $id; $i++) {
					    $thname = $gruz[$i];
					    if ($thname == 'Побочный продукт сталеплавильного производства') { $thname = 'Побочный продукт'; }
					    if ($thname == 'Б/У кирпич магнезитный') { $thname = 'Кирп. магн.'; }
					    if ($thname == 'Б/у кирпич шамотный') { $thname = 'Кирп. шамот.'; }
					    if ($thname == 'СКРАП 200 плюс') { $thname = 'СКРАП 200+'; }
					    if ($thname == 'СКРАП 400 плюс') { $thname = 'СКРАП 400+'; }
					    if ($thname == 'СКРАП 500-800 мм') { $thname = 'СКРАП 500 - 800'; }
					    if ($thname == 'Щебень 0-20 мм.') { $thname = 'Щебень 0-20'; }
					    if ($thname == 'Щебень 20-40 мм.') { $thname = 'Щебень 20-40'; }
					    if ($thname == 'Щебень 20-70 мм.') { $thname = 'Щебень 20-70'; }
						    echo '<th>'.$thname.'</th>';
						}
				     
				      
			echo '<th scope="col">Всего</th>
				    </tr>
				  </thead>
				  <tbody>';
				$sql = "SELECT GRUZ_NAME, FIRMA_POL, SUM(NETTO), DATETIME_CREATE FROM weighing WHERE ".$vyborka." GROUP BY FIRMA_POL";
				$sql = $pdoves->prepare($sql);
				$sql->execute();
				$sql = $sql->fetchAll(PDO::FETCH_BOTH);
									
				foreach ($sql as $result) {
				$polych = $result['FIRMA_POL'];
				
				if ($polych != 'ОБОСОБЛЕННОЕ ПОДРАЗДЕЛЕНИЕ СТРОЙПЛОЩАДКА,  ООО"АЛЬФА ИНЖЕНЕРИНГ и КОНСТРАКШН"') {
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
				if ($polych == 'ООО «Рубеж В»') { $polych = 'Рубеж В'; }
				if ($polych == 'ООО «Нижегородская металлургическая компания»') { $polych = 'НЛМК'; }
				if ($polych == 'ООО СтимСтрой') { $polych = 'СтимСтрой'; }
				if ($polych == 'ТЕПЛОЭНЕРГОМОНТАЖ') { $polych = 'Тепл. Энр. Монтаж'; }
				if (empty($polych)) { $polych = '<i class="fas fa-train"></i> Ж/Д';}
				echo '<tr><th scope="row">'.$polych.'</span></th>';
					  
					  for ($i = 1; $i <= $id; $i++) {
						  
						  $sql2 = 'SELECT SUM(NETTO) FROM `weighing` WHERE FIRMA_POL="'.$result["FIRMA_POL"].'" and GRUZ_NAME="'.$gruz[$i].'" and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"';
						  		
						  		$sql2 = $pdoves->prepare($sql2);
								$sql2->execute();
								$sql2 = $sql2->fetchAll(PDO::FETCH_BOTH);
													
								foreach ($sql2 as $result2) {
							  		
							  		
							  		
						  			$netto = ($result2['SUM(NETTO)']/1000);
						  			if ($netto == 0) {
							  			$netto = '';
						  			}
						  			echo '<td>'.$netto.'</td>';
						   		 }
						}
						
						$allkontr = (DBOnce2('SUM(NETTO)','weighing','FIRMA_POL="'.$result['FIRMA_POL'].'" and '.$vyborka))/1000;
						echo '<td>'.$allkontr.'</td></tr>';
					  
					  }
					  
					  
				    }
				    
				     
					   
				  echo '<tr class="font-weight-bold"><th></th>';
				  for ($i = 1; $i <= $id; $i++) {
						  
						  $sql2 = "SELECT SUM(NETTO) FROM `weighing` WHERE GRUZ_NAME='".$gruz[$i]."' and TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59'";
						  $sql2 = $pdoves->prepare($sql2);
								$sql2->execute();
								$sql2 = $sql2->fetchAll(PDO::FETCH_BOTH);
													
								foreach ($sql2 as $result2) {
						  			$netto = ($result2['SUM(NETTO)']/1000);
							  			if ($netto == 0) {
								  			$netto = '';
							  			}
						  			echo '<td>'.$netto.'</td>';
						   		 }
						}
				  
				$allkontr = (DBOnce2('SUM(NETTO)','weighing',$vyborka))/1000;
				
					   echo '<td>'.$allkontr.'</td></tr>
			</tbody>
		</table></div>';
		
		// запись в лог
	 	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	 	$action = 'Сформировал общий отчет по отгрузкам. Период: '.$period1.' - '.$period2;
	 	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
?>
