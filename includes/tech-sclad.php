<?php
global $pdo;	
global $iduser;
if (isset($_POST["addzap"])) {
	$name = $_POST["namezap"];
	$type = $_POST["typezap"];
	$count = $_POST["countzap"];
	$tech = $_POST["techzap"];
	
	$sql = $pdo->prepare("INSERT INTO tech_oil SET name = :name, type = :type, count = :count, tech = :tech");
	$sql->execute(array('name' => $name, 'type' => $type, 'count' => $count, 'tech' => $tech));	
	
	if ($sql) {
		successmes($url);
	} else {
		errormes($url);
	}
}

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
	
	$iddel = $_POST['del_id'];
	$deletesql = $pdo->prepare('DELETE from `tech_oil` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}

if (isset($_POST['nameTech'])) { //проверяем, есть ли переменная
	
	$idtech = $_POST['idtech'];
	$newName = $_POST['nameTech'];
	$updatework = 'UPDATE tech_oil SET name = "'.$newName.'" WHERE id = "'.$idtech.'"';
    $updatework = $pdo->prepare($updatework);
	$updatework->execute();
    if ($updatework) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}

$zaplist = DB('*','tech_oil','');	
$tech = DB('*','tech_tech','');
?>
<a data-toggle="collapse" href="#collapseExample" id="addNewZap" role="button" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary btn-sm mb-3"><i class="fas fa-plus mr-2"></i>Добавить новую запчасть</a>

<div class="collapse" id="collapseExample">
	<div class="card mb-3">
		<div class="card-body">
			<form method="post">
			  <div class="row mb-3"><div class="col-sm-3">Наименование</div><div class="col-sm-9"><input type="text" class="form-control" name="namezap" placeholder="Н-р: Шелл гидравлический Tellus S2V 46" autocomplete="off"></div></div>
			  <div class="row mb-3"><div class="col-sm-3">Тип</div><div class="col-sm-9">
				  <select name="typezap" class="form-control">
					  <option value="Масло">Масло</option>
					  <option value="Фильтр">Фильтр</option>
				  </select>
				</div></div>
			  <div class="row mb-3"><div class="col-sm-3">Текущее кол-во</div><div class="col-sm-9"><input type="number" class="form-control" name="countzap" placeholder="Н-р: 5" autocomplete="off"></div></div>
			  <div class="row mb-3"><div class="col-sm-3">Привязать к технике?</div><div class="col-sm-9">
				  <select name="techzap" class="form-control">
					  <option value="0" selected>Не привязывать</option>
					  <?php foreach ($tech as $n) { ?>
					  <option value="<?=$n['id']?>"><?=$n['name']?></option>
					  <?php } ?>
				  </select>
			  </div></div>
			  <div class="row mb-3"><div class="col-sm-3"></div><div class="col-sm-9"><button type="submit" name="addzap" class="btn btn-primary mb-2"><i class="fas fa-plus mr-2"></i>Добавить запчасть</button></div></div>
		  
		</form>
		</div>
	</div>
</div>	


<div class="card mt-3">
	<div class="card-body">
<p><input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Введите название запчасти..."></p>
        <div class="mb-3">
            <?php foreach ($tech as $techUnit): ?>
            <a href="#tech<?= $techUnit['id'] ?>" class="mr-1 btn"><?= $techUnit['name'] ?></a>
            <?php endforeach; ?>
        </div>
        <div class="table-responsive">
					<table class="table table-hover" id="logtable">
						  <thead>
						    <tr>
						      <th scope="col">Название</th>
						      <th scope="col">Тип</th>
						      <th scope="col">Кол-во</th>
						      <th scope="col"><i class="fas fa-pencil-alt text-primary delbut" style="cursor: pointer"></i></th>
						    </tr>
						  </thead>
						  <tbody>
							  <tr class="table-active"><td>Запчасти без привязки к технике
								 <span style="display: none">
							<?php $tech1 = DB('*','tech_oil','tech="0"'); foreach ($tech1 as $n1) { echo $n1['name'].' '; } ?>
							</span> 
								 
							  </td><td></td><td></td><td></td></tr>
<?php $tech1 = DB('*','tech_oil','tech="0"');
	       	 foreach ($tech1 as $n) { ?>
		   	 	<tr>
					<td><p class="pt-2 mb-0"><?=$n['name']?></p>
					
					
					
					<div class="collapse" id="editName<?=$n['id']?>">
						<form method="post">
							<input value="<?=$n['id']?>" name="idtech" hidden/>
							<input class="form-control mt-3 mb-3" value="" name="nameTech" placeholder="Введите новое имя для запчасти">
							<button type="submit" class="btn btn-primary btn-small">Изменить имя</button>
						</form>
					</div>
					
					</td>
					<td><p class="pt-2 mb-0"><?=$n['type']?></p></td>
					<td><p class="pt-2 mb-0 font-weight-bold"><?=$n['count']?></p></td>
					<td>
						<div class="rashod">
							<form method="get" action="/tech-add/">
								<input name="techid" value="0" hidden/>
								<input name="zapid" value="<?=$n['id']?>" hidden/>
							<button class="btn small w-100 btn-primary"><i class="fas fa-wrench mr-3"></i>Списать/Добавить</button>
							</form>
						</div>
						
						
						<div class="d-none editBLock">
						<div class="mr-3">
						<a data-toggle="collapse" href="#editName<?=$n['id']?>" role="button" aria-expanded="false" aria-controls="editName<?=$n['id']?>"><i class="fas fa-pencil-alt"></i></a>
						</div>
						<div>
						<form method="post" class="mb-0"><input type="text" class="form-control hidden" name="del_id" value="<?=$n['id']?>"><button type="submit" class="btn delsub btn-link p-0" disabled><i class="fas fa-trash-alt text-danger"></i></button></form>
						</div>
						</div>
					</td>
				</tr>
			<?php } ?>							  
					<?php
						$tech = DB('*','tech_tech','');
						
						foreach ($tech as $n) : ?>
							
							<tr class="table-active" id="tech<?= $n['id'] ?>"><td><?=$n['name']?>
							<span style="display: none">
							<?php $tech1 = DB('*','tech_oil','tech='.$n['id']); foreach ($tech1 as $n1) { echo $n1['name'].' '; } ?>
							</td><td></td><td></td><td></td></tr>
							
			<?php $tech1 = DB('*','tech_oil','tech='.$n['id']);
	       	 foreach ($tech1 as $n1) { ?>
		   	 	<tr>
					<td><p class="pt-2 mb-0"><?=$n1['name']?></p>
					
					
					
					<div class="collapse" id="editName<?=$n1['id']?>">
						<form method="post">
							<input value="<?=$n1['id']?>" name="idtech" hidden/>
							<input class="form-control mt-3 mb-3" value="" name="nameTech" placeholder="Введите новое имя для запчасти">
							<button type="submit" class="btn btn-primary btn-small">Изменить имя</button>
						</form>
					</div>
					
					</td>
					<td><p class="pt-2 mb-0"><?=$n1['type']?></p></td>
					<td><p class="pt-2 mb-0 font-weight-bold"><?=$n1['count']?></p></td>
					<td>
						<div class="rashod">
							<form method="get" action="/tech-add/">
								<input name="techid" value="<?=$n['id']?>" hidden/>
								<input name="zapid" value="<?=$n1['id']?>" hidden/>
							<button class="btn small w-100 btn-primary"><i class="fas fa-wrench mr-3"></i>Списать/Добавить</button>
							</form>
						</div>
						
						
						<div class="d-none editBLock">
						<div class="mr-3">
						<a data-toggle="collapse" href="#editName<?=$n1['id']?>" role="button" aria-expanded="false" aria-controls="editName<?=$n1['id']?>"><i class="fas fa-pencil-alt"></i></a>
						</div>
						<div>
						<form method="post" class="mb-0"><input type="text" class="form-control hidden" name="del_id" value="<?=$n1['id']?>"><button type="submit" class="btn delsub btn-link p-0" disabled><i class="fas fa-trash-alt text-danger"></i></button></form>
						</div>
						</div>
					</td>
				</tr>
			<?php } ?>							
							
							
						<?php endforeach;?>
 					</tbody>
				</table>
        </div>
	</div>
</div>



<div class="info-block">
	<div class="inside table-responsive">
		<table class="table table-hover">
		  	<thead>
			    <tr>
			      <th scope="col">Техника</th>
			      <th scope="col">Масло</th>
			      <th scope="col">Кол-во</th>
			      <th scope="col">Дата</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		<?php
			$sql2 = "SELECT *  FROM oil ORDER BY id DESC LIMIT 300";
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
			    echo '<tr><td>'.$tech.'</td><td>'.$zapname.'</td><td>'.$n['value'].$edin.'.</td><td>'.$newDate3.'</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$n['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		    }
		?>
			</tbody>
		</table>
	</div>
</div>

<script>
$(document).ready(function () {
	$('.delbut').on('click', function () {
		$('.rashod').addClass('d-none');
		$('.editBlock').removeClass('d-none').addClass('d-flex');
        $('.delsub').prop('disabled', false);
    });

	
	$(".countzap").bind('keyup change click', function () {
		if (! $(this).data("previousValue") || 
           $(this).data("previousValue") != $(this).val()
       )
   {
        console.log("changed");           
        $(this).data("previousValue", $(this).val());
        count = $(this).val();
        id = $(this).attr('id');
    	$.post("/ajax.php", {zap: id, count: count},controlUpdate);
    	function controlUpdate(data) {
			$('#' + id).css("color", "#007bff");
			setTimeout(function () {
				$('#' + id).css("color", "#495057");
			}, 1500);
		}
     
   }
		           
	});
});
</script>

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