<?php
$period1 = date("d.m", strtotime($datestart));
$period2 = date("d.m", strtotime($dateend));
$workscount = DBOnce('COUNT(*) as count','tech_report','id!=0 and datetime between "'.$datestart.'" and "'.$dateend.'"');

if ($workscount > 0) {

$sql = DB('*','tech_report','id!=0 and datetime between "'.$datestart.'" and "'.$dateend.'"');

foreach ($sql as $n) { 
$newDate = date("d.m", strtotime($n['datetime']));
?>

<div class="card mb-3">
	<div class="card-header font-weight-bold"><?=$newDate?></div>
	<div class="card-body"><?=$n['report']?></div>
</div>

<?php } } else { ?>
<p class="text-center mt-3 mb-3">Отчетов за данный период нет</p>
<?php } 
	
$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
$action = 'Сформировал отчет по механикам. Период: '.$period1.' - '.$period2;
$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
?>