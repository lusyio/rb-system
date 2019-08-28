<?php $countTo = DBOnce('count(*)','tech_work','status="done" and tech='.$tech); ?>
<div class="card pl-3"><a href="/tech/" class="ml-1 mr-2"><i class="fas fa-arrow-left"></i> К списку техники</a></div>
<div class="card mt-3">
<div class="card-header">
    <h5 class="mb-0 font-weight-bold"><?=DBOnce('name','tech_tech','id='.$tech)?></h5>
  </div>
  <div class="card-body">
	
		  <input type="text" id="tech1" class="hidden" value="<?=$tech?>">
		  <div class="form-group mb-2">
		    <label for="staticEmail2">Остаток топлива</label>
		    <input type="number" id="toplivo" min="0" max="9999999999" class="form-control" placeholder="Топливо в литрах" autocomplete="off" required>
		  </div>
		  <div class="form-group">
		    <label for="staticEmail2">Моточасы</label>
		    <input type="number" id="motchas" min="0" max="9999999999" class="form-control" placeholder="Моточасы" autocomplete="off" required>
		  </div>
		  <button id="addpok" class="btn btn-success mr-3">Добавить <i class="fas fa-check"></i></button>
		  <?php if($countTo > 0) { ?>
		  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
		    Показать завершенные ТО
		  </button>
		 
		  	<div class="collapse mt-3" id="collapseExample">
				  <div class="card card-body pb-1">
				    <?php
					    $to = DB('*','tech_work','status="done" and tech='.$tech.' order by datetime DESC');
					    
						foreach ($to as $n) { 
							$date = date("d.m", strtotime($n['datetime']));
							
						?>
							
							<p>ТО для <strong><?=DBOnce('name','tech_tech','id='.$tech)?></strong> с наработкой <strong><?=$n['motchas']?> м/ч</strong> выполнено <?=$date?></p>
							
							
					<?php	} ?>
				    
				    
				  </div>
			</div>
			 <?php } ?>
  </div>

<?php
$normcount = DBOnce('COUNT(*) as count','tech_norm','tech='.$tech);
if (empty($normcount)) {
	echo '<hr><p class="text-center mt-5 mb-5">Данных по данной технике нет</p>';
} else { 
$norm = DB('*','tech_norm','tech='.$tech.' order by datetime DESC');
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
							<button class="delnorm btn btn-link p-0" value="<?=$n['id']?>"><i class="fas fa-trash-alt text-danger"></i></button>
						</td>
					</tr>
			  
			  <?php	} ?>
		  </tbody>
	</table>
<?php } ?>
</div>
<script>var tech3 = <?=$tech?>; </script>
<script src="/js/tech.js"></script>