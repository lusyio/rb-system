<?php
	$tech2 = DBOnce('tech','tech_norm','id='.$tech);
	$deletesql = $pdo->prepare('DELETE from `tech_norm` WHERE `id` = :id');
	$deletesql->execute(array('id' => $tech));
	echo $tech2;
?>