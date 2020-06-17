<?php
$normcount = DBOnce('COUNT(*) as count','tech_norm','tech='.$tech);
if (empty($normcount)) {
	echo '<hr><p class="text-center mt-5 mb-5">Данных по данной технике нет</p>';
} else {
$norm = DB('*','tech_norm','tech='.$tech.' order by datetime DESC limit 5');
$techName = DBOnce('name', 'tech_tech', 'id=' . $tech);
$startTime = date('Y-m-d H:i:s',strtotime('-30 days midnight'));
$startTimeDisplay = date('d.m',strtotime('-30 days midnight'));
$endTimeDisplay = date('d.m');
$workDays = DBOnce('COUNT(*)','tech_norm','tech='.$tech.' AND datetime >= "' . $startTime . '"');
$oilCount = DBOnce("SUM(toplivo)",
    "gsm",
    "tech = '" . $techName . "' and date >= '" . $startTime . "'");
if (!$oilCount) {
    $oilCount = 0;
}
if ($workDays == 0) {
    $avgCount = 0;
} else {
    $avgCount = round($oilCount / $workDays, 1);
}
function getNumeral($number, $n1, $n2, $n5) {
    if ($number % 100 > 10 && $number % 100 < 15) {
        return $n5;
    } elseif ($number % 10 == 1) {
        return $n1;
    } elseif ($number % 10 > 1 && $number % 10 < 5) {
        return $n2;
    } else {
        return $n5;
    }
}
?>
    <p class="tooltip2" style="margin-bottom: 8px">
        Среднесуточный расход - <strong><?= $avgCount ?> л.</strong>
        <span class="tooltiptext">С <?= $startTimeDisplay ?> по <?= $endTimeDisplay ?> было заправлено <?= $oilCount ?> <?= getNumeral($oilCount, 'литр', 'литра', 'литров')?>. Техника работала <?= $workDays ?> <?= getNumeral($workDays, 'день', 'дня', 'дней')?>. <?= $oilCount ?>/<?= $workDays ?>=<?= $avgCount ?></span>
    </p>
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
