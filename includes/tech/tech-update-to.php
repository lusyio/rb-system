<?php
$toId = $_POST['toId'];
$zap = $_POST['zaplist'];

$sql = $pdo->prepare("UPDATE `tech_to` SET `zap` = :zap WHERE id = :toId");
$sql->execute(array(':zap' => $zap, ':toId' => $toId));
echo $tech . ' ' . $zap . ' ' . $toId;
