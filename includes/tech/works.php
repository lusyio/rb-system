<?php
	$sql = DB('*','tech_norm','tech='.$tech);
	if(!empty($sql)) {				
	foreach ($sql as $result) {
		echo '<option value="'.$result['id'].'">'.$result['name'].'</option>';
			  
	}
	} else {
		echo '<option selected="true" disabled="disabled">! Нормативов нет !</option>';
	}
?>