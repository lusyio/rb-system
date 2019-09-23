<?php $zaplist = DB('*','tech_oil','tech="'.$tech.'" AND is_deleted = 0');
	$oils = DB('*','tech_oil','tech="0" AND is_deleted = 0');
	
	foreach ($zaplist as $n) { ?>
		<option value="<?=$n['id']?>"><?=$n['name']?></option>
<?php } ?>
<?php	foreach ($oils as $n) { ?>
										<option value="<?=$n['id']?>"><?=$n['name']?></option>
								<?php } ?>