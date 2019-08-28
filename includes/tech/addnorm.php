<?php
$motchas = $_POST['motchas'];
$toplivo = $_POST['toplivo'];


$sql = $pdo->prepare("INSERT INTO `tech_norm` SET `motchas` = :motchas, `toplivo` = :toplivo, `tech` = :tech, `datetime` = :datetime");
$sql->execute(array('motchas' => $motchas, 'toplivo' => $toplivo, 'tech' => $tech, 'datetime' => $datetime));
echo 'Добавлено';
?>