<?php
    $period1 = date("d.m", strtotime($datestart));
    $period2 = date("d.m", strtotime($dateend));
    $id = 0;
    if ($iduser == 20) {
        $anti = " and GRUZ_NAME != 'СКРАП 15-50' ";
    } else {
        $anti = "";
    }
    $vyborka = "TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '".$datestart." 00:00:00' and '".$dateend." 23:59:59' and GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм'".$anti." and GRUZ_NAME != ''";
    $fullDataQuery = $pdoves->prepare("SELECT GRUZ_NAME, FIRMA_POL, SUM(NETTO) AS SUM_NETTO FROM weighing WHERE " . $vyborka . "GROUP BY GRUZ_NAME, FIRMA_POL");
    $fullDataQuery->execute();
    $fullData = $fullDataQuery->fetchAll(PDO::FETCH_ASSOC);
    $polychNames = [];
    $gruzNames = [];
    $polychSum = [];
    $gruzSum = [];
    $resultData = [];
    $total = 0;
    foreach ($fullData as $otgruzka) {
        $polychIndex = array_search($otgruzka['FIRMA_POL'], $polychNames);
        if ($polychIndex === false) {
            $polychNames[] = $otgruzka['FIRMA_POL'];
            $polychIndex = count($polychNames) - 1;
            $polychSum[$polychIndex] = 0;
        }
        $gruzIndex = array_search($otgruzka['GRUZ_NAME'], $gruzNames);
        if ($gruzIndex === false) {
            $gruzNames[] = $otgruzka['GRUZ_NAME'];
            $gruzIndex = count($gruzNames) - 1;
            $gruzSum[$gruzIndex] = 0;
        }
        $resultData[$polychIndex][$gruzIndex] = $otgruzka['SUM_NETTO'];
        $polychSum[$polychIndex] += $otgruzka['SUM_NETTO'];
        $gruzSum[$gruzIndex] += $otgruzka['SUM_NETTO'];
        $total += $otgruzka['SUM_NETTO'];
    }
    asort($polychNames);
    asort($gruzNames);
?>
<h5>Отгрузки с <?= $period1 ?> 00:00 по <?= $period2 ?> 23:59</h5>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-sm table-striped mt-3">
        <thead>
			<tr>
				<th scope="col">Организации</th>
				<?php foreach ($gruzNames as $gruzName):
                    $thname = $gruzName;
                    if ($thname == 'Побочный продукт сталеплавильного производства') { $thname = 'Побочный продукт'; }
                    if ($thname == 'Б/У кирпич магнезитный') { $thname = 'Кирп. магн.'; }
                    if ($thname == 'Б/у кирпич шамотный') { $thname = 'Кирп. шамот.'; }
                    if ($thname == 'СКРАП 200 плюс') { $thname = 'СКРАП 200+'; }
                    if ($thname == 'СКРАП 400 плюс') { $thname = 'СКРАП 400+'; }
                    if ($thname == 'СКРАП 500-800 мм') { $thname = 'СКРАП 500 - 800'; }
                    if ($thname == 'Щебень 0-20 мм.') { $thname = 'Щебень 0-20'; }
                    if ($thname == 'Щебень 20-40 мм.') { $thname = 'Щебень 20-40'; }
                    if ($thname == 'Щебень 20-70 мм.') { $thname = 'Щебень 20-70'; }
				?>
				<th><?= $thname ?></th>
				<?php endforeach; ?>
				<th scope="col">Всего</th>
			</tr>
		</thead>
		<tbody>
     	<?php foreach ($polychNames AS $polychIndex => $polychName):
            if ($polychName == 'ОБОСОБЛЕННОЕ ПОДРАЗДЕЛЕНИЕ СТРОЙПЛОЩАДКА,  ООО"АЛЬФА ИНЖЕНЕРИНГ и КОНСТРАКШН"') {
                continue;
            }
            if ($polychName == 'ОООСтройТехМеханизация') { $polychName = 'СТМ'; }
            if ($polychName == 'Администрация городского округа г. Выкса') { $polychName = 'Администрация'; }
            if ($polychName == 'ЗАО ПМК ВЫКСУНСКАЯ') { $polychName = 'ПМК'; }
            if ($polychName == 'ООО Асфальтный завод Сарова') { $polychName = 'Саров'; }
            if ($polychName == 'ОАО «Рязаньавтодор»') { $polychName = 'Рязаньавтодор'; }
            if ($polychName == 'Фьючерс. Красовский') { $polychName = 'Красовский (ф)'; }
            if ($polychName == 'сотрудникам РУБЕЖА-В') { $polychName = 'Сотрудникам'; }
            if ($polychName == 'ООО «Птицекомплекс ВыксОВО»') { $polychName = 'Птицекомплекс'; }
            if ($polychName == 'ОООАРСЕНАЛ') { $polychName = 'АРСЕНАЛ'; }
            if ($polychName == 'ООО Грин Строй') { $polychName = 'Грин Строй';  }
            if ($polychName == 'ООО Дана') { $polychName = 'Дана'; }
            if ($polychName == 'ОООУКРусККом') { $polychName = 'УКРусККом'; }
            if ($polychName == 'ООО СканГруз') { $polychName = 'СканГруз'; }
            if ($polychName == 'ООО «Рубеж В»') { $polychName = 'Рубеж В'; }
            if ($polychName == 'ООО «Нижегородская металлургическая компания»') { $polychName = 'НЛМК'; }
            if ($polychName == 'ООО СтимСтрой') { $polychName = 'СтимСтрой'; }
            if ($polychName == 'ТЕПЛОЭНЕРГОМОНТАЖ') { $polychName = 'Тепл. Энр. Монтаж'; }
            if (empty($polychName)) { $polychName = '<i class="fas fa-train"></i> Ж/Д';}
            ?>
			<tr>
                <th scope="row"><?= $polychName ?></span></th>
                <?php foreach ($gruzNames AS $gruzIndex => $gruzName):
                    if (isset($resultData[$polychIndex][$gruzIndex])) {
                        $netto = $resultData[$polychIndex][$gruzIndex] / 1000;
                    } else {
                        $netto = '';
                    }
                    if ($netto == 0) {
                        $netto = '';
                    }
                ?>
                <td><?= $netto ?></td>
                <?php endforeach; ?>
                <td><?= $polychSum[$polychIndex] / 1000 ?></td>
            </tr>
        <?php endforeach; ?>
            <tr class="font-weight-bold"><th></th>
                <?php foreach ($gruzNames as $gruzIndex => $gruzName):
                $netto = $gruzSum[$gruzIndex];
                if ($netto == 0) {
                    $netto = '';
                }
                ?>
                <td><?= $netto / 1000; ?></td>
                <?php endforeach; ?>
                <td><?= $total / 1000 ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
    // запись в лог
    $log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
    $action = 'Сформировал общий отчет по отгрузкам. Период: '.$period1.' - '.$period2;
    $log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
