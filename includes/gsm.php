<?php	
if (isset($_POST["toplivo"])) {
	
	// pdo
	global $pdo;
		
	// datetime и путь
	$url = $_SERVER['REQUEST_URI'];
	$datetime = date("Y-m-d H:i:s");
		
	// топливо в бочке
	$sql = "SELECT `toplivo` FROM `gsm` WHERE `id`='1'";
	$row = $pdo->query($sql);
	$result = $row->fetch();
	$a = $result[0]; 
	
	$tech = $_POST['tech'];
	$date = $_POST['date'];
	$toplivo = $_POST['toplivo'];
	
	// вставка в таблицу гсм
	$sql = $pdo->prepare("INSERT INTO `gsm` SET `tech` = :tech, `date` = :date, `toplivo` = :toplivo");
	$sql->execute(array('tech' => $tech, 'date' => $date, 'toplivo' => $toplivo));
	
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Добавил ГСМ: '.$toplivo.' л. Техника: '.$tech.'. Топливо в бочке было - '.$a;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	if ($sql) {
	
	$a = $a - $toplivo;
	$newbochka = $pdo->prepare('UPDATE `gsm` SET toplivo = :toplivo where id="1"');
	$newbochka->execute(array('toplivo' => $a));
	successmes($url);
	    } else {
	errormes($url);
	}
}

	$alltech = DB('*','tech_tech','id!=0');
?>
<?php $now = date("Y-m-d"); ?>
<div class="info-block">
	<div class="header-block">
		<h3>Добавить показания ГСМ</h3>
	</div>
<div class="inside">
<form method="post">
	<div class="form-group row">
	    <label for="staticEmail" class="col-sm-3 col-form-label">Техника</label>
	    <div class="col-sm-9">
		    <select name="tech" class="form-control">
			  <?php foreach ($alltech as $n) { ?>
			  <option value="<?=$n['name']?>"><?=$n['name']?></option>
			 <?php } ?>
			  <option value="Mitsubishi Pajero">Mitsubishi Pajero</option>
			  <option value="LONG">LONG</option>
			  <option value="Гараж">Гараж</option>
			  <option value="Технические потери">Технические потери</option>
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
      <label for="staticEmail" class="col-sm-3 col-form-label">Кол-во топлива (л)</label>
      <div class="col-sm-9">
        <input type="number" value="" min="1" max="9999" step="1" class="form-control" name="toplivo" value="" placeholder="Н-р: 30 - пишем только цифры">
      </div>
    </div>	        
	<button type="submit" name="Submit" class="btn btn-primary">Добавить данные</button>
</form>
</div>
</div>
<?php


// топливо в бочке
$sql = "SELECT `toplivo` FROM `gsm` WHERE `id`='1'";
$row = $pdo->query($sql);
$result = $row->fetch();
$bochka = $result[0];

if (isset($_POST["toplivo-new"])) {
		
	// datetime и путь
	$url = $_SERVER['REQUEST_URI'];
	$datetime = date("Y-m-d H:i:s");
	$newtoplivo = $_POST['toplivo-new'];
	$bochkanew = $bochka + $newtoplivo;
	
	// обновим бочку
	
	$newbochka = $pdo->prepare('UPDATE `gsm` SET toplivo = :toplivo where id="1"');
	$newbochka->execute(array('toplivo' => $bochkanew));
	
	// запишем в лог
	
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Заправил бочку на '.$newtoplivo.' л. Топливо в бочке было до - '.$bochka.', а стало '.$bochkanew;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	if($newbochka) {
		successmes($url);
	} else {
		errormes($url);
	}
}
?>
<div class="info-block bochka-block">
	<div class="inside">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				<?php
				     echo '<p class="bochka"><strong>Топлива в бочке:</strong><br>'.$bochka.' л.</p>';
				?>
			</div>
			<div class="col-xs-12 col-sm-9">
				<form method="post">
					<div class="form-group row">
					    <div class="col-sm-8">
					        <p><input type="number" value="" min="1" max="9999999" step="1" class="form-control" name="toplivo-new" placeholder="Кол-во топлива"></p>
					    </div>
					    <div class="col-sm-4">
						    <p><button type="submit" name="Submit" class="btn btn-warning">Заправить</button></p>
					    </div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
	
	$url = '/gsm/';
	// datetime и путь
	
	$datetime = date("Y-m-d H:i:s");
	
	// топливо в бочке
	$sql = "SELECT `toplivo` FROM `gsm` WHERE `id`='1'";
	$row = $pdo->query($sql);
	$result = $row->fetch();
	$bochka = $result[0];
	
	$iddel = $_POST['del_id'];
	$sql = "SELECT toplivo, tech, date  FROM `gsm` WHERE id='".$iddel."'";
	$tech = $pdo->prepare($sql);
	$tech->execute();
	$techs = $tech->fetchAll(PDO::FETCH_BOTH);
	
	foreach ($techs as $n) {
		$top = $n['toplivo'];
		$tech = $n['tech'];
        $date = $n['date'];
	}
	
	$bochkanew = $bochka + $top;
	
	// обновим бочку
	
	$newbochka = $pdo->prepare('UPDATE `gsm` SET toplivo = :toplivo where id="1"');
	$newbochka->execute(array('toplivo' => $bochkanew));
	
	$deletesql = $pdo->prepare('DELETE from `gsm` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
	
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Удалил ГСМ: '.$top.' л. Техника: '.$tech.'. Топливо в бочке было - '.$bochka;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	
    if ($deletesql) {
	successmes($url);
	    } else {
	errormes($url);
	}
	
	
}
?>
<div class="info-block gsm-date">
	<div class="inside">
		<div class="row text-center">
		<!-- 5 дней -->	
		<?php
			$sql2 = "SELECT date, SUM(toplivo) AS user_sum FROM `gsm` GROUP BY date DESC LIMIT 4";
			$tech2 = $pdo->prepare($sql2);
			$tech2->execute();
			$techs2 = $tech2->fetchAll(PDO::FETCH_BOTH);
		    foreach ($techs2 as $n) {
			    $newDate3 = date("d.m", strtotime($n['date']));
			    echo '<div class="col-xs-3 toplsut"><h4>'.$n['user_sum'].'<span>л.</span></h4><p>'.$newDate3.'</p></div>';
		    }
		?>
		</div>
	</div>
</div>
<div class="info-block">
	<div class="inside">
		<table class="table table-hover">
		  	<thead>
			    <tr>
			      <th scope="col">Техника</th>
			      <th scope="col">Дата</th>
			      <th scope="col">Топливо</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		<?php
			$sql2 = "SELECT id, tech, date, toplivo  FROM gsm WHERE id > 2 ORDER BY id DESC LIMIT 150";
			$tech2 = $pdo->prepare($sql2);
			$tech2->execute();
			$techs2 = $tech2->fetchAll(PDO::FETCH_BOTH);
		    foreach ($techs2 as $n) {
			    $newDate3 = date("d.m", strtotime($n['date']));
			    echo '<tr><td>'.$n['tech'].'</td><td>'.$newDate3.'</td><td>'.$n['toplivo'].' л.</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$n['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		    }
		?>
			</tbody>
		</table>
	</div>
</div>

