<?php
	$to = $_POST['to'];
	$zap = $_POST['zaplist'];
	
	
$sql = $pdo->prepare("INSERT INTO `tech_to` SET `tech` = :tech, `zap` = :zap, `type` = :type");
$sql->execute(array('tech' => $tech, 'zap' => $zap, 'type' => $to));
echo $tech . ' ' . $zap . ' ' . $to;

?>