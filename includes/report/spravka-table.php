<?php
echo ' <thead>
				    <tr>
				      <th scope="col" align="center">№</th>
				      <th scope="col" align="center">№ вагона</th>
				      <th scope="col" align="center">Грузоподъёмность<br>вагона, т.</th>
				      <th scope="col" align="center">Тара, кг</th>
				      <th scope="col" align="center">Брутто, кг</th>
				      <th scope="col" align="center">Нетто, кг</th>';
				      if (empty($_POST["idreport2"])) {
					      echo ' <th scope="col" align="center">Груз</th>
				      <th scope="col" align="center">Дата</th>';
				      }
				     
				   echo '</tr>
				  </thead>
				  <tbody>';
				    	$sql = $pdoves->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
	foreach ($sql as $result) {
					 $i = $i + 1;
					$newDate2 = date("d.m.Y H:i", strtotime($result['DATETIME_CREATE']));	  
					$tara = $result['TARA']/1000;
					$ves = $result['NETTO']/1000;
					echo '<tr><td>'.$i.'</td><td>'.$result['NOMER_TS'].'</td><td>70</td><td>'.$result['TARA'].'</td><td>'.$result['BRUTTO'].'</td><td>'.$result['NETTO'].'</td>';
					if (empty($_POST["idreport2"])) {
					      echo '<td>'.$result['GRUZ_NAME'].'</td>
					<td>'.$newDate2.'</td>';
				      }
					
					echo'
					</tr>';
					  
				    }
 echo '</tbody></table>';
?>