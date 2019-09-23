<?php
if (isset($_GET['zapid'])) {
    $zapid = filter_var($_GET['zapid'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $zapid = '';
}
if (isset($_GET['techid'])) {
    $techid = filter_var($_GET['techid'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $techid = 0;
}
if ($techid == 0) {
    $techidOption = 59;
} else {
    $techidOption = $techid;
}
// вывод техники
$tech = DB('*','tech_tech','');
$zaplist = DB('*','tech_oil','tech="'. $techid .'"');
$tolist = DB('*','tech_to','id != 0 group by tech');
function arrayzap($array) {
	$results = [];
	$data    = explode("/", $array);
	foreach ($data as $row) {
	    $line = explode(":", $row);
	    $results[] = [$line[0], $line[1]];
	}
	return $results;
}
if (isset($_POST['delto'])) { //проверяем, есть ли переменная
	
	$url = '/tech-to/';
	$iddel = $_POST['delto'];
	$deletesql = $pdo->prepare('DELETE from `tech_to` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}
if (isset($_POST['remove'])) {
    
	// datetime и путь
	$url = $_SERVER['REQUEST_URI'];
	$datetime = date("Y-m-d H:i:s", strtotime($_POST['datezap']));
	
	$tech = $_POST['tech'];
	$oil = $_POST['zaplist'];
	$date = $datetime;
	$value = $_POST['countzap'];
	$type = 0;
	
	// вставка в таблицу oil
	$sql = $pdo->prepare("INSERT INTO `oil` SET `tech` = :tech, `oil` = :oil, `datetime` = :datetime, `value` = :value, `type` = :type");
	$sql->execute(array('tech' => $tech, 'oil' => $oil, 'datetime' => $date, 'value' => $value, 'type' => $type));
	
	$oldvalue = DBOnce('count','tech_oil','id='.$oil);
	
	$newvalue = $oldvalue - $value;
	
	$updatework = 'UPDATE tech_oil SET count = "'.$newvalue.'" WHERE id = "'.$oil.'"';
	$updatework = $pdo->prepare($updatework);
	$updatework->execute();
	
	
	$oil = DBOnce('name','tech_oil','id='.$oil);
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Добавил расход запчасти: '.$oil;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	if ($sql) {
	successmes($url);
	    } else {
	errormes($url);
	}
}
if (isset($_POST['addSclad'])) {
	// datetime и путь
	$url = $_SERVER['REQUEST_URI'];
	$datetime = date("Y-m-d H:i:s", strtotime($_POST['datezap']));
	
	$tech = $_POST['tech'];
	$oil = $_POST['zaplist'];
	$date = $datetime;
	$value = $_POST['countzap'];
	$type = 1;
	
	// вставка в таблицу oil
	$sql = $pdo->prepare("INSERT INTO `oil` SET `tech` = :tech, `oil` = :oil, `datetime` = :datetime, `value` = :value, `type` = :type");
	$sql->execute(array('tech' => $tech, 'oil' => $oil, 'datetime' => $date, 'value' => $value, 'type' => $type));
	
	$oldvalue = DBOnce('count','tech_oil','id='.$oil);
	
	$newvalue = $oldvalue + $value;
	
	$updatework = 'UPDATE tech_oil SET count = "'.$newvalue.'" WHERE id = "'.$oil.'"';
	$updatework = $pdo->prepare($updatework);
	$updatework->execute();
	
	
	$oil = DBOnce('name','tech_oil','id='.$oil);
	// запись в лог
	$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
	$action = 'Добавил запчасть на склад: '.$oil;
	$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
	
	if ($sql) {
	successmes($url);
	    } else {
	errormes($url);
	}
}
?>
<style>
	.form-control {
		 height: 34px;
	}
</style>

<div class="card">
	<div class="card-body">
		
 		<form method="post">
			
			<div class="row mb-3">
				
				<div class="col-sm-2 font-weight-bold"><i class="fas fa-truck-monster mr-3"></i>Техника</div>
				<div class="col-sm-10">
					
					<select name="tech" id="tech" class="form-control mb-1">
						<?php foreach ($tech as $n) { ?>
						<option value="<?=$n['id']?>"<?= ($n['id'] == $techidOption) ? ' selected' : '' ?>><?=$n['name']?></option>
						<?php } ?>
						
					</select>
					<small class="text-secondary">Выберите технику, для которой берем запчасть со склада или добавляем</small>
				</div>
			</div>

			<div class="row mb-3">
				
				<div class="col-sm-2 font-weight-bold"><i class="fas fa-wrench mr-3"></i>Запчасти</div>
				<div class="col-sm-10 zapBlock">
					
					<div class="row mb-3 zapBlockIn">
						<div class="col-sm-8">
							<select name="zaplist" class="form-control mb-1 zaplist">
								
								<?php foreach ($zaplist as $n) { ?>
									<option value="<?=$n['id']?>" <?= ($n['id'] == $zapid) ? ' selected' : '' ?>><?=$n['name']?></option>
								<?php } ?>
								
							</select>
							<small class="text-secondary">Запчасть</small>
						</div>
						
				
						<div class="col-sm-4">
							
							<input class="form-control countzap mb-1" name="countzap" type="number" min="1" placeholder="Н-р: 5"/>
							<small class="text-secondary">Кол-во (шт. или л.)</small>
						</div>
					</div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <input type="date" class="form-control mb-1" name="datezap" value="<?= $now ?>">
                            <small class="text-secondary">Дата</small>
                        </div>
                    </div>
					<div class="row">
						<div class="col-sm-6">
							<button type="submit" name="remove" class="btn btn-primary w-100"><i class="fas fa-minus mr-2"></i>Списать запчасть</button>
						</div>
						<div class="col-sm-6">
							<button type="submit" name="addSclad" class="btn btn-success w-100"><i class="fas fa-plus mr-2"></i>Добавить на склад</button>
						</div>
					</div>
				</div>
			</div>
			
</form> 
	</div>
</div>

<div class="info-block">
	<div class="inside">
		<table class="table table-hover">
		  	<thead>
			    <tr>
			      <th scope="col">Зап.часть</th>
			      <th scope="col"><i class="fas fa-exchange-alt"></i></th>
			      <th scope="col">Техника</th>
			      <th scope="col">Кол-во</th>
			      <th scope="col">Дата</th>
			      <th scope="col" class="d-none"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		<?php
			$sql2 = "SELECT *  FROM oil ORDER BY datetime DESC LIMIT 300";
			$tech2 = $pdo->prepare($sql2);
			$tech2->execute();
			$techs2 = $tech2->fetchAll(PDO::FETCH_BOTH);
		    foreach ($techs2 as $n) {
			    $newDate3 = date("d.m", strtotime($n['datetime']));
			    $tech = DBOnce('name','tech_tech','id='.$n['tech']);
			    $zapname = DBOnce('name','tech_oil','id='.$n['oil']);
			    $typezap =  DBOnce('type','tech_oil','id='.$n['oil']);
		 		if ($typezap == 'Масло') {
			 		$edin = ' л';
		 		} else {
			 		$edin = ' шт';
		 		}
		 		$type = $n['type'];
		 		if ($type == 0) {
			 		$type = '<i class="fas fa-minus text-danger" title="Списание запчасти"></i>';
		 		} else {
			 		$type = '<i class="fas fa-plus text-success" title="Добавление на склад"></i>';
		 		}
			    echo '<tr><td>'.$zapname.'</td><td>'.$type.'</td><td>'.$tech.'</td><td>'.$n['value'].$edin.'.</td><td>'.$newDate3.'</td><td class="d-none"><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$n['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		    }
		?>
			</tbody>
		</table>
	</div>
</div>

		
<script>
$(document).ready(function () {
	$('#tech').bind('keyup change click', function () {
		value = $('#tech :selected').val();
		addSelect(value);
	});

	function addSelect(id) {
		id = $('#tech').val();
    	$.post("/ajax.php", {technorm: id, info: 'tech-ajax-select'},controlUpdate);
    	function controlUpdate(data) {
			$(".zaplist").empty().html(data);
		}
	}

    $('#makeTO').on('click', function () {
	    id = $('#tech').val();
	    to = $('#to').val();
	    console.log('start');
	    var zap = '';
        $('.zapBlockIn').each(function(){
		  var zapChast = $('.zaplist', this).val();
		  var zapCount = $('.countzap', this).val();
		  zap += zapChast + ':' + zapCount + '/';
		});
		zap = zap.slice(0,-1);
		console.log(zap);
		if (zap != '') {

		$.post("/ajax.php", {technorm: id, to: to, zaplist:zap, info: 'tech-add-to'},controlUpdate);
    	function controlUpdate(data) {

			swal({
			title: 'Успешно',
			text: '',
			type: 'success',
			allowOutsideClick: false,
				}).then(function (result) {
					if (result.value) {
					window.location = '/tech-to/';
		  		}
			})
		}
		}
    });
});
</script>