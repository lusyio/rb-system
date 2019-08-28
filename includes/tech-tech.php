<?php
if (isset($_POST["idtech"])) {
	$idtech = $_POST["idtech"];
	$filename = 'images/'.$idtech.'.jpg';
	if (file_exists($filename)) {
		$imgname = $idtech;
	} else {
		$imgname = 'no-image';
	} 
	$nameTech = DBOnce('name','tech_tech','id='.$idtech);
	$mch = DBOnce('motchas','tech_norm','tech='.$idtech.' order by datetime DESC');
	if ($mch > 0) {
		$mch = '<span style="white-space: nowrap;">' . numb($mch) . ' Мч</span>';
	} else {
		$mch = '<span class="text-muted">н.з.</span>';
	}
}
if (isset($_POST['delmot'])) { //проверяем, есть ли переменная
	$iddel = $_POST['delmot'];
	$url = '/tech-work/';
	$deletesql = $pdo->prepare('DELETE from `tech_norm` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}
?>
<?php if (!empty($idtech)) : ?>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-10">
				<h4 class="font-weight-bold"><?=$nameTech?></h4>
				<small class="text-secondary"><?=$mch?></small>
			</div>
			<div class="col-2">
				<img src="/images/<?=$imgname?>.jpg" style=" position: absolute; top: -20px; right: 0; height: 95px; ">
			</div>
		</div>
	</div>
</div>

<div class="d-none mt-2">
	<button class="btn btn-light bg-white border mr-2"><i class="fas fa-tachometer-alt mr-3"></i>Внести показания топлива</button>
	<button class="btn btn-light bg-white border mr-2"><i class="fas fa-oil-can mr-3"></i>Учет масла</button>
	<button class="btn btn-light bg-white border"><i class="fas fa-clone mr-3"></i>Учет фильтров</button>
</div>
<div class="card card-body pb-1 mt-3">
				    <?php
					    $to = DB('*','tech_work','status="done" and tech='.$idtech.' order by datetime DESC');
					    
						foreach ($to as $n) { 
							$date = date("d.m", strtotime($n['datetime']));
							
						?>
							
							<p><strong>ТО <?=$n['type']?></strong> для <strong><?=DBOnce('name','tech_tech','id='.$idtech)?></strong> с наработкой <strong><?=$n['motchas']?> м/ч</strong> выполнено <?=$date?></p>
							
							
					<?php	} ?>
				    
				    
				  </div>
<div class="card mt-3">
	<div class="card-body">
<?php
$normcount = DBOnce('COUNT(*) as count','tech_norm','tech='.$idtech);
if (empty($normcount)) {
	echo '<hr><p class="text-center mt-5 mb-5">Данных по данной технике нет</p>';
} else { 
$norm = DB('*','tech_norm','tech='.$idtech.' order by datetime DESC');
?>
	<table class="table mb-0">
			  <thead>
			    <tr class="table-secondary">
			      <th scope="col" class="pl-4">Моточасы</th>
			      <th scope="col">Остаток топлива</th>
			      <th scope="col">Дата</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
			  </thead>
		  <tbody>
			  <?php foreach ($norm as $n) { 
				  $date = date("d.m", strtotime($n['datetime']));
			  ?>
			  		
			  		<tr id="norm-<?=$n['id']?>">
						<td class="pl-4 font-weight-bold"><?=$n['motchas']?></td>
						<td class="text-muted"><?=$n['toplivo']?></td>
						<td class="text-muted"><?=$date?></td>
						<td width="50px">
							<form method="post">
								<input value="<?=$n['id']?>" name="delmot" hidden/>
								<button type="submit" class="delnorm btn btn-link p-0" ><i class="fas fa-trash-alt text-danger"></i></button>
							</form>
						</td>
					</tr>
			  
			  <?php	} ?>
		  </tbody>
	</table>
<?php } ?>
	</div>
</div>
<?php endif; ?>