<?php	
if (isset($_POST["toplivo"])) {
	
	// pdo
	global $pdo;
		
	// datetime и путь
	$url = $_SERVER['REQUEST_URI'];
	$datetime = date("Y-m-d H:i:s");
	
	$tech = $_POST['tech'];
	$oil = $_POST['oil'];
	$date = $_POST['date'];
	$value = $_POST['toplivo'];
	
	// вставка в таблицу oil
	$sql = $pdo->prepare("INSERT INTO `oil` SET `tech` = :tech, `oil` = :oil, `datetime` = :datetime, `value` = :value");
	$sql->execute(array('tech' => $tech, 'oil' => $oil, 'datetime' => $date, 'value' => $value));
	
	$oldvalue = DBOnce('count','tech_oil','id='.$oil);
	
	$newvalue = $oldvalue - $value;
	
	$updatework = 'UPDATE tech_oil SET count = "'.$newvalue.'" WHERE id = "'.$oil.'"';
	$updatework = $pdo->prepare($updatework);
	$updatework->execute();
	
	$tech = DBOnce('name','tech_tech','id='.$tech);
	$oil = DBOnce('name','tech_oil','id='.$oil);
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Добавил расход масла: '.$oil.' л. Техника: '.$tech;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	if ($sql) {
	successmes($url);
	    } else {
	errormes($url);
	}
}

	$alltech = DB('*','tech_tech','id!=0');
	$alloil = DB('*','tech_oil','id!=0 AND is_deleted = 0');
?>
<?php $now = date("Y-m-d"); ?>
<div class="info-block">
	<div class="header-block">
		<h3>Добавить учет расхода масла</h3>
	</div>
<div class="inside">
<form method="post">
	<div class="form-group row">
	    <label for="staticEmail" class="col-sm-3 col-form-label">Техника</label>
	    <div class="col-sm-9">
		    <select name="tech" class="form-control">
			  <?php foreach ($alltech as $n) { ?>
			  <option value="<?=$n['id']?>"><?=$n['name']?></option>
			 <?php } ?>
			</select>
	    </div>
	</div>
	<div class="form-group row">
	    <label for="staticEmail" class="col-sm-3 col-form-label">Масло</label>
	    <div class="col-sm-9">
		    <select name="oil" class="form-control">
			    <?php foreach ($alloil as $n) { ?>
					<option value="<?=$n['id']?>"><?=$n['name']?></option>
				<?php } ?>
			</select>
	    </div>
	</div>
	<div class="form-group row">
	    <label for="staticEmail" class="col-sm-3 col-form-label">Дата</label>
	    <div class="col-sm-9">
	        <input type="date" class="form-control" name="date" value="<?=$now?>">
	    </div>
	</div>
    <div class="form-group row">
      <label for="staticEmail" class="col-sm-3 col-form-label">Кол-во (л)</label>
      <div class="col-sm-9">
        <input type="number" value="" min="1" max="9999" step="1" class="form-control" name="toplivo" value="" placeholder="Н-р: 30 - пишем только цифры">
      </div>
    </div>	        
	<button type="submit" name="Submit" class="btn btn-primary">Добавить данные</button>
</form>
</div>
</div>

<?php

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
	
	$url = '/oil/';
	// datetime и путь
	
	$datetime = date("Y-m-d H:i:s");
	
	
	$iddel = $_POST['del_id'];
	
	$value = DBOnce('value','oil','id='.$iddel);
	$tech = DBOnce('tech','oil','id='.$iddel);
	$oil = DBOnce('oil','oil','id='.$iddel);
	$tech = DBOnce('name','tech_tech','id='.$tech);
	$newDate3 = date("d.m", strtotime(DBOnce('datetime','oil','id='.$iddel)));
	
	$deletesql = $pdo->prepare('DELETE from `oil` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));

	$oldvalue = DBOnce('count','tech_oil','id='.$oil);
	
	$newvalue = $oldvalue + $value;
	
	$updatework = 'UPDATE tech_oil SET count = "'.$newvalue.'" WHERE id = "'.$oil.'"';
	$updatework = $pdo->prepare($updatework);
	$updatework->execute();
	
		
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Удалил из учета масла: '.$value.' л. Техника: '.$tech.'. от даты - '.$newDate3;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	
    if ($deletesql) {
	successmes($url);
	    } else {
	errormes($url);
	}
	
	
}
?>
<div class="info-block">
	<div class="inside">
		<table class="table table-hover">
		  	<thead>
			    <tr>
			      <th scope="col">Техника</th>
			      <th scope="col">Масло</th>
			      <th scope="col">Кол-во (л.)</th>
			      <th scope="col">Дата</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		<?php
			$sql2 = "SELECT *  FROM oil ORDER BY id DESC LIMIT 150";
			$tech2 = $pdo->prepare($sql2);
			$tech2->execute();
			$techs2 = $tech2->fetchAll(PDO::FETCH_BOTH);
		    foreach ($techs2 as $n) {
			    $newDate3 = date("d.m", strtotime($n['datetime']));
			    $tech = DBOnce('name','tech_tech','id='.$n['tech']);
			    $oil = DBOnce('name','tech_oil','id='.$n['oil']);
			    echo '<tr><td>'.$tech.'</td><td>'.$oil.'</td><td>'.$n['value'].' л.</td><td>'.$newDate3.'</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$n['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		    }
		?>
			</tbody>
		</table>
	</div>
</div>

