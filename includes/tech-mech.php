<?php
if (isset($_POST["addtech"])) {
	$name = $_POST["addtech"];
	// pdo
	global $pdo;	
	// вставка в таблицу техники
	
	$sql = $pdo->prepare("INSERT INTO `workers` SET `name` = :name, otdel = 'Механики'");
	$sql->execute(array('name' => $name));	
	if ($sql) {
		successmes($url);
	} else {
		errormes($url);
	}
}

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
	
	$url = '/tech-mech/';
	$iddel = $_POST['del_id'];
	$deletesql = $pdo->prepare('DELETE from `workers` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}

// вывод техники
$tech = DB('*','workers','');
?>


<div class="card">
  <div class="card-header">
    <h5 class="mb-0">Механики <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" class="ml-3"><i class="fas fa-plus"></i></a></h5>
  </div>
  <div>
  <div class="collapse" id="collapseExample">

		<form method="post" class="form-inline pl-4 mb-0 mt-2">
		  <div class="form-group mb-2">
		    <label for="staticEmail2" class="sr-only">ФИО</label>
		    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Наименование">
		  </div>
		  <div class="form-group mx-sm-3 mb-2">
		    <label for="addtech" class="sr-only">Tech</label>
		    <input type="text" class="form-control" name="addtech" id="addtech" placeholder="Н-р: Иванов И.И." autocomplete="off">
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
				foreach ($tech as $n) { ?>
					<tr>
						<td class="pl-4 text-muted" width="50px">#<?=$i?></td>
						<td class="font-weight-bold"><?=$n['name']?></td>
						<td width="50px"><form method="post" class="mb-0"><input type="text" class="form-control hidden" name="del_id" value="<?=$n['id']?>"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td>
					</tr>
			<?php $i++;	} ?>
			

<?php
} ?>
			</tbody>
		</table>
  </div>
</div>
