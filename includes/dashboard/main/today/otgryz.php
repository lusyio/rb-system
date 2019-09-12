<?php
	$otrgyz = DBOnce2('COUNT(*) as count','weighing','(GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow);
	if ($otrgyz == 0) {
        return;
    }

$sql = 'SELECT GRUZ_NAME, FIRMA_POL, SUM(NETTO) AS SUM_NETTO FROM weighing WHERE (GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE '.$bwnow.' GROUP BY FIRMA_POL, GRUZ_NAME';
		    $sql = $pdoves->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
$polychNames = [];

$gruzNames = [
    'Песок шлаковый 0-5 мм',
    'Щебень 5-20 мм',
    'Щебень 0-20 мм.',
    'Щебень 20-40 мм.',
    'Щебень 20-70 мм.',
];
$polychSum = [];
$gruzSum = [];
$resultData = [];
foreach ($sql as $otgruzka) {
    $polychIndex = array_search($otgruzka['FIRMA_POL'], $polychNames);
    if ($polychIndex === false) {
        $polychNames[] = $otgruzka['FIRMA_POL'];
        $polychIndex = count($polychNames) - 1;
        $polychSum[$polychIndex] = 0;
    }
    $gruzIndex = array_search($otgruzka['GRUZ_NAME'], $gruzNames);
    if (!isset($gruzSum[$gruzIndex])) {
        $gruzSum[$gruzIndex] = 0;
    }
    $resultData[$polychIndex][$gruzIndex] = $otgruzka['SUM_NETTO'];
    $polychSum[$polychIndex] += $otgruzka['SUM_NETTO'];
    $gruzSum[$gruzIndex] += $otgruzka['SUM_NETTO'];

}
asort($polychNames);
foreach ($gruzSum as $gruzIndex => $value) {
    if ($value == 0) {
        unset($gruzNames[$gruzIndex]);
    }
}

	$cars = $otrgyz;
	$allshebtoday = array_sum($gruzSum);

	if ($cars % 10 >= 5 || $cars % 10 == 0) {$carname = 'машин';}
	elseif ($cars % 10 == 1) {$carname = 'машину';}
	else {$carname = 'машины';}

	echo '<p>Сегодня мы отгрузили '.round($allshebtoday/1000).'т. щебня ('.$cars.' '.$carname.'):</p>';

?>

<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col"></th>
            <?php foreach ($gruzNames as $gruzName):
                $displayName = '';
            if ($gruzName == 'Песок шлаковый 0-5 мм') {$displayName = '0-5';}
            if ($gruzName == 'Щебень 5-20 мм') {$displayName = '5-20';}
            if ($gruzName == 'Щебень 0-20 мм.') {$displayName = '0-20';}
            if ($gruzName == 'Щебень 20-40 мм.') {$displayName = '20-40';}
            if ($gruzName == 'Щебень 20-70 мм.') {$displayName = '20-70';}
            ?>
             <th scope="col"><?= $displayName ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody id="widj-otgryz">
        <?php
        foreach ($polychNames as $polychIndex => $polychName):
            $polych = $polychName;
            if ($polych == 'ОООСтройТехМеханизация') { $polych = 'СТМ'; }
            if ($polych == 'Администрация городского округа г. Выкса') { $polych = 'Администрация'; }
            if ($polych == 'ЗАО ПМК ВЫКСУНСКАЯ') { $polych = 'ПМК'; }
            if ($polych == 'ООО Асфальтный завод Сарова') { $polych = 'Саров'; }
            if ($polych == 'ОАО «Рязаньавтодор»') { $polych = 'Рязаньавтодор'; }
            if ($polych == 'Фьючерс. Красовский') { $polych = 'Красовский (ф)'; }
            if ($polych == 'сотрудникам РУБЕЖА-В') { $polych = 'Сотрудникам'; }
            if ($polych == 'ООО «Птицекомплекс ВыксОВО»') { $polych = 'Птицекомплекс'; }
            if ($polych == 'ОООАРСЕНАЛ') { $polych = 'АРСЕНАЛ'; }
            if ($polych == 'ООО Грин Строй') { $polych = 'Грин Строй';  }
            if ($polych == 'ООО Дана') { $polych = 'Дана'; }
            if ($polych == 'ОООУКРусККом') { $polych = 'УКРусККом'; }
            if ($polych == 'ООО СканГруз') { $polych = 'СканГруз'; }
            if ($polych == 'ООО СтимСтрой') { $polych = 'СтимСтрой'; }
            if ($polych == 'ОООДСУЕрмишинский') { $polych = 'ДСУЕрмиш'; }
            if ($polych == 'ЗАО «Управление Механизированных работ - 10 »') { $polych = 'УМР-10'; }
            ?>
            <tr>
                <td><?= $polych ?></td>
                <?php
                foreach ($gruzNames as $gruzIndex => $gruzName):
                    if (isset($resultData[$polychIndex][$gruzIndex])) {
                        $netto = round($resultData[$polychIndex][$gruzIndex] / 1000);
                    } else {
                        $netto = 0;
                    }
                    ?>
                <th scope="col"><?=$netto?></th>
            <?php endforeach; ?>
            </tr>
        <?php endforeach;?>
            <th scope="col"></th>
            <?php foreach ($gruzNames as $gruzIndex => $gruzName):?>
            <th scope="col"><?= round($gruzSum[$gruzIndex] / 1000) ?></th>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
