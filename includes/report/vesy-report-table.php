<?php
echo	'<thead>
				    <tr>
				      <th scope="col">Дата</th>
				      <th scope="col">Номер ТС</th>';
				      if (empty($polychatel)) {
					      echo '<th scope="col">Получатель</th>';
				      } else {
					      
				      }
				  echo '<th scope="col">Груз</th>';
				  if ($polychatel == 'АО ВМЗ') {
					      echo '<th scope="col">№ ТТН</th>';
				      } else {
					      
				      }
				  echo   '<th scope="col">Тара</th>
				      <th scope="col">Нетто</th>';
				      
				echo '    </tr>
				  </thead>
				  <tbody>';
				  
				  
				  $sql = $pdoves->prepare($sql);
				  $sql->execute();
				  $sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
				  foreach ($sql as $result) {
					  
					$newDate2 = date("d.m.Y H:i", strtotime($result['DATETIME_CREATE']));	  
					$tara = $result['TARA']/1000;
					$ves = $result['NETTO']/1000;
					echo '<tr><td>'.$newDate2.'</td><td>'.$result['NOMER_TS'].'</td>';
					if (empty($polychatel)) {
					      echo '<td>'.$result['FIRMA_POL'].'</d>';
				      } else {
					      
				      }
					
					echo '<td>'.$result['GRUZ_NAME'].'</td>';
					if ($polychatel == 'АО ВМЗ') {
					      echo '<td>'.$result['DOC'].'</td>';
				      } else {
					      
				      }
					echo '<td>'.$tara.' т.</td><td>'.$ves.' т.</td>';
					
					
				echo	'</tr>';
					  
				    }
				    
				  $all2 = $all/1000;
				  echo '<tr><td> </td><td> </td>';
				  if (empty($polychatel)) {
					      echo '<td></d>';
				      } else {
					      
				      }
				  echo '<td></td>';
				  if ($polychatel == 'АО ВМЗ') {
					      echo '<td></td>';
				      } else {
					      
				      }
				  echo '<td><strong>Всего:</strong></td><td><strong>'.$all2.' т.</strong></td>';
				    
				  echo '</tr>
			</tbody>
		</table>';
?>