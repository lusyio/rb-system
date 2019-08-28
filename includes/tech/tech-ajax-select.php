<?php $zaplist = DB('*','tech_oil','tech="'.$tech.'"'); 
	$oils = DB('*','tech_oil','tech="0"');
	
	foreach ($zaplist as $n) { ?>
		<option value="<?=$n['id']?>"><?=$n['name']?></option>
<?php } ?>
<?php	foreach ($oils as $n) { ?>
										<option value="<?=$n['id']?>"><?=$n['name']?></option>
								<?php } ?>