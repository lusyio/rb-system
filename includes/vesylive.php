<div class="info-block">
<div class="table-responsive">
<table class="table table-hover table-striped">
				  <thead>
				    <tr>
				      <th scope="col">Груз</th>
				      <th scope="col">Вес</th>
				      <th scope="col">Время</th>
				    </tr>
				  </thead>
				  <tbody>
					  <?php
	
	$sql = "SELECT GRUZ_NAME, FIRMA_POL, TYP_EVENT, VESYNAME, NETTO, DATETIME_CREATE FROM `weighing` WHERE GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and TYP_EVENT='Реализация (отгрузка покупателю)' or TYP_EVENT='Покупка (приход от поставщика)' ORDER BY DATETIME_CREATE DESC LIMIT 50";
		    $sql = $pdoves->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {
			    
			   $newDate2 = date("d.m в H:i", strtotime($result['DATETIME_CREATE']));
			   if ($result['VESYNAME'] == 'Весы 3') {
				   $train = '<i class="fas fa-train"></i>';
			   } else {
				   $train = '';
			   }
			   if ($result['GRUZ_NAME'] == 'СКРАП 15-50' and $iduser == 20) {
				
				}  else {
					echo '<tr><td><p>'.$result['GRUZ_NAME'].' '.$train.'</p><p class="small">'.$result['FIRMA_POL'].'</p><p class="small">'.$result['TYP_EVENT'].'</p></td><td>'.$result['NETTO'].'</td><td>'.$newDate2.'</td></tr>';
				} 
			    
		    }

?>
</tbody>
</table>
</div>
</div>