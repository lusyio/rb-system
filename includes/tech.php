<?php
if (isset($_POST["addtech"])) {
	$name = $_POST["addtech"];
	// pdo
	global $pdo;	
	// вставка в таблицу техники
	
	$sql = $pdo->prepare("INSERT INTO `tech_tech` SET `name` = :name");
	$sql->execute(array('name' => $name));	
	if ($sql) {
		successmes($url);
	} else {
		errormes($url);
	}
}

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
	
	$url = '/tech/';
	$iddel = $_POST['del_id'];
	$deletesql = $pdo->prepare('DELETE from `tech_tech` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}

if (isset($_POST["work"])) {
	global $pdo;
	$updatework = 'UPDATE tech_work SET status = "done", datetime = "'.$datetime.'" WHERE id = "'.$_POST["work"].'"';
    $updatework = $pdo->prepare($updatework);
	$updatework->execute();
	
	$idtech = DBOnce('tech','tech_work','id='.$_POST["work"]);
	$nametech = DBOnce('name','tech_tech','id='.$idtech);
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Завершил ТО для '.$nametech;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
    if ($updatework) {
		successmes($url);
	} else {
		errormes($url);
	}
}
// вывод техники
$tech = DB('*','tech_tech','');
?>
<div id="tech">
<div class="card" >
  <div class="card-header">
    <h5 class="mb-0">Единицы техники <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" class="ml-3"><i class="fas fa-plus"></i></a></h5>
  </div>
  <div>
  <div class="collapse" id="collapseExample">

		<form method="post" class="form-inline pl-4 mb-0 mt-2">
		  <div class="form-group mb-2">
		    <label for="staticEmail2" class="sr-only">Наименование</label>
		    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Наименование">
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		    <label for="addtech" class="sr-only">Tech</label>
		    <input type="text" class="form-control" name="addtech" id="addtech" placeholder="Н-р: CAT 320" autocomplete="off">
		  </div>
		  <button type="submit" class="btn btn-primary mb-2">Добавить</button>
		</form>
	</div>	
<?php
if (!empty($tech)) { ?>

  
	  <table class="table mb-0">
		  <tbody>
			
			<?php 
				$i = 1;
				foreach ($tech as $n) { 
					$tech = $n['id'];
					$to = DBOnce('motchas','tech_work','status="inwork" and tech='.$n['id']);
					
					$mch = DBOnce('motchas','tech_norm','tech='.$n['id'].' order by datetime DESC');
					
					$interval = 500;
					$otschet = 0;
					
					if ($tech == 41 or $tech == 42 ) {
						$interval = 500;
					} 
					if ($tech == 41) {
						$otschet = 8700;
					}	
					if ($tech == 42) {
						$otschet = 5500;
					}	
					if ($tech == 46) {
						$otschet = 25267;
					}
					
					$new = (okr(($mch - $otschet) / $interval)*$interval) - ($mch - $otschet);
					
					if (!empty($to)) {
						
						if ($new >= 31 and $new <= 100) {
							$new = '<span class="text-warning font-weight-bold">Скоро ТО</span>';
						} else if ($new < 30) {
							$new = '<form method="post" class="mb-0"><input class="hidden" name="work" value="' . DBOnce('id','tech_work','tech='.$n['id'].' order by datetime DESC') . '"><button type="submit" class="btn btn-danger btn-sm">Завершить ТО <i class="fas fa-check"></i></button></form>';
						}
						
					} else {
						$new = '';
					}
					
					if ($mch > 0) {
						$mch = '<span style="white-space: nowrap;">' . numb($mch) . ' Мч</span>';
					} else {
						$mch = '<span class="text-muted">н.з.</span>';
					}
				?>
					<tr>
						<td class="pl-4 text-muted"><span style="white-space: nowrap;">#<?=$i?></span></td>
						<td>
							<button name="tech-button" value="<?=$n['id']?>" style=" line-height: 1;position: relative;top: -2px; " class="tech-button btn btn-link p-0 font-weight-bold"><?=$n['name']?></button> <br class="visible-sm">- <?=$mch?>
						</td>
						<td><?=$new?></td>
<!-- 						<td width="50px"><form method="post" class="mb-0"><input type="text" class="form-control hidden" name="del_id" value="<?=$n['id']?>"><button type="submit" class="btn btn-link p-0 d-none"><i class="fas fa-trash-alt text-danger"></i></button></form></td> -->
					</tr>
			<?php $i++;	} ?>
			

<?php
} ?>
			</tbody>
		</table>
  </div>
</div>


<div class="card mt-3">
	<div class="card-header"><h5 class="mb-0">Журнал</h5></div>
	<div class="card-body">
<table class="table mb-0 table-hover">
	<thead>
    <tr>
      <th scope="col">Техника</th>
      <th scope="col">Мот.ч.</th>
      <th scope="col">Топ-во</th>
      <th scope="col">Дата</th>
    </tr>
  </thead>
		  <tbody>
<?php 
	$norm = DB('*','tech_norm','id!=0 order by datetime DESC limit 50');
	
	
	foreach ($norm as $n) { 
		
	$date = date("d.m", strtotime($n['datetime']));
	$tech = DBOnce('name','tech_tech','id='.$n['tech']);
?>
	<tr>
						<td class="font-weight-bold"><?=$tech?></td>
						<td><?=numb($n['motchas'])?></td>
						<td><?=$n['toplivo']?></td>
						<td><?=$date?></td>
					</tr>
<?php } ?>
</tbody>
		</table>
	</div>
</div>
</div>
<script src="/js/tech.js"></script>