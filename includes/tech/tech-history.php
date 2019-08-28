<?php
$normcount = DBOnce('COUNT(*) as count','tech_norm','tech='.$tech);
if (empty($normcount)) {
	echo '<hr><p class="text-center mt-5 mb-5">Данных по данной технике нет</p>';
} else { 
$norm = DB('*','tech_norm','tech='.$tech.' order by datetime DESC limit 5');
?>
	<table class="table mb-0">
			  <thead>
			    <tr class="table-secondary">
			      <th scope="col" class="pl-4">Моточасы</th>
			      <th scope="col">Остаток топлива</th>
			      <th scope="col">Дата</th>
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
					</tr>
			  
			  <?php	} ?>
		  </tbody>
	</table>
<?php } ?>
