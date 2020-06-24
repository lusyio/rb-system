<?php
// Касса
		$kassa = 'kassa';

		$rashod = DBOnce('SUM(value) AS sumvalue',$kassa,'type="Расход" and date between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"');
		$prihod = DBOnce('SUM(value) AS sumvalue',$kassa,'type="Приход" and date between "'.$datestart.' 00:00:00" and "'.$dateend.' 23:59:59"');


		$allcash = number_format($prihod-$rashod,0,'',' ');
		$period1 = date("d.m", strtotime($datestart));
		$period2 = date("d.m", strtotime($dateend));

			// запись в лог
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = 'Сформировал отчет по кассе (Евдокимов). Период: '.$period1.' - '.$period2;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
?>
		<h4><?=$allcash;?> руб.</h4>
		<p>Общий итог по кассе Евдокимова за период <?=$period1;?> - <?=$period2;?> Расход: <?=numb($rashod);?> руб. Приход: <?=numb($prihod);?> руб.</p>
		<p><input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Поиск по названию операции..."></p>
		<table class="table table-hover" id="logtable">
		  	<thead>
			    <tr>
			    	<th>Наименование</th>
					<th>Сумма</th>
					<th>Дата</th>
			    </tr>
		  	</thead>
		  	<tbody>
					<?php
			    $sql = "SELECT type, value, what, date FROM ".$kassa." WHERE id > 2 and date between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' ORDER BY date DESC";
			    $sql = $pdo->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);

						foreach ($sql as $result) {
				    $newDate2 = date("d.m", strtotime($result['date']));
				    $value = number_format($result['value'],0,'',' ');

				    if ($result['type'] == 'Приход') {
						$summa = '<span class="text-success"><strong>+'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
					}

					if ($result['type'] == 'Расход') {
						$summa = '<span class="text-danger"><strong>-'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
					}


			        echo '<tr><td>'.$result['what'].'</td><td style="min-width:170px">'.$summa.'</td><td>'.$newDate2.'</td></tr>';
			        }
			   echo '
			</tbody>
		</table>';
?>
<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("logtable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
