<div class="info-block gsm-date">
	<div class="inside">
	<h2>Добавить смену</h2>
	<p>Последняя добавленная смена - <?php 
		
		$sql = "SELECT datestart, brigadir, daynight FROM `brigada` ORDER BY id DESC LIMIT 1";  
		$sql = $pdo->prepare($sql);
				$sql->execute();
				$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
				foreach ($sql as $result) {
		if ($result['daynight'] == 'day') { $dainight = '<i class="fas fa-sun text-warning"></i>'; } if ($result['daynight'] == 'night') { $dainight = '<i class="fas fa-moon text-primary"></i>'; } if ($result['daynight'] == 'megaday') { $dainight = '<i class="fas fa-star text-warning"></i>'; } $lastsmen = date("d.m.Y", strtotime($result['datestart'])).' - '.$result['brigadir'].' - '.$dainight; echo $lastsmen; } ?> </p>
	<hr>
	
	

	<?php
	//Если переменная Name передана
	if (isset($_POST["brigadir"])) {
		
		
		
		if ($_POST['timesmen'] == "day") {
			$datestart = $_POST['datesmen'];
			$timestart = "07:00:00";
			$dateend = $_POST['datesmen'];
			$timeend = '18:59:59';
			
		} 
		
		if ($_POST['timesmen'] == "night") {
			$datestart = $_POST['datesmen'];
			$date = new DateTime($datestart);
			$date->modify('+1 day');
			$tommorow = $date->format('Y-m-d');
			$timestart = "19:00:00";
			$dateend = $tommorow;
			$timeend = '06:59:59';
		}
                  
$idsm = DBOnce('id','brigada','datestart = "'.$datestart.'" and daynight = "'.$_POST['timesmen'].'"');
if(empty($idsm)) {
	// вставка в таблицу бригада
	$sql = $pdo->prepare("INSERT INTO `brigada` SET `datestart` = :datestart, `timestart` = :timestart, `dateend` = :dateend, `timeend` = :timeend, `daynight` = :daynight, `brigadir` = :brigadir, `report` = 'no'");
	$sql->execute(array('datestart' => $datestart, 'timestart' => $timestart, 'dateend' => $dateend, 'timeend' => $timeend, 'daynight' => $_POST['timesmen'], 'brigadir' => $_POST['brigadir']));
	if ($sql) {
		successmes($url);
	} else {
		errormes($url);
	}
}	else {
		errormes($url);
	}
}	
		
	?>
	

	
	<form method="post">
		<div class="form-group">
	      <label for="date" class="col-form-label"><i class="far fa-calendar-alt mr-2"></i> Дата началы смены</label>
	        <input type="date" class="form-control" name="datesmen" value="<?php echo $now; ?>">
	    </div>
	    <div class="form-group">
	      <label for="staticEmail" class="col-form-label"><i class="far fa-clock mr-2"></i> Время</label>
	        <select name="timesmen" class="form-control">
				<option value="day">Дневная</option>
				<option value="night">Ночная</option>
			</select>
	    </div>
	    <div class="form-group">
	      <label for="staticEmail" class="col-form-label"><i class="far fa-user mr-2"></i> Бригадир</label>
	        <select name="brigadir" class="form-control">
                <option value="Туманов">Туманов</option>
				<option value="Вилков">Вилков</option>
				<option value="Мартынов">Аксенов</option>
				<option value="Венников">Венников</option>
				<option value="Есин">Есин</option>
                <option value="Рыкин">Рыкин</option>
			</select>
	    </div>
	    <button type="submit" name="Submit" class="btn btn-primary mt-3 mb-3">Добавить смену <i class="fas fa-plus ml-2"></i></button>
	</form>
<?php
if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
 

$delid = $_POST['del_id'];

$deletesql = $pdo->prepare('DELETE from brigada WHERE `id` = :id');
$deletesql->execute(array('id' => $delid));	

	if ($deletesql) {
	successmes($url);
	    } else {
	errormes($url);
	}	

} 

		
		
		
		$sql = 'SELECT * FROM brigada WHERE datestart >= "'.$now.'"';
		$sql = $pdo->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		
		if (!empty($sql)) {
		?>
		<table class="table table-hover table-striped w-100">
		  	<thead>
			    <tr>
			      <th scope="col">Смена</th>
			      <th scope="col">Дата</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		 <?php
		foreach ($sql as $result) {		
			$newDate = date("d.m", strtotime($result['datestart']));
			if ($result['daynight'] == 'day') { $dainight = '<i class="fas fa-sun text-warning"></i>'; } 
			if ($result['daynight'] == 'night') { $dainight = '<i class="fas fa-moon text-primary"></i>'; } 
			$lastsmen = $result['brigadir'].' - '.$dainight; 
			echo '<tr><td><p>'.$lastsmen.'</p></td><td>'.$newDate.'</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$result['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		} ?>
		</tbody>
		</table>
		<?php
		}
	?>
	</div>
</div>