<?php
//include '../../../bdcon.php';
global $pdo;
global $pdoves;
global $now;
global $yesterday;

// заготовки
//$now = '2019-08-27';
//$yesterday = '2019-08-26';
$bwnow = 'between "' . $yesterday . ' 20:00:00" and "' . $now . ' 19:59:59"';

// информация по сменам

// определяем id ночной и дневной смены
$nightSmenaId = DBOnce('id', 'brigada', 'datestart = "' . $yesterday . '" and timestart = "19:00:00"');
$daySmenaId = DBOnce('id', 'brigada', 'datestart = "' . $now . '" and timestart = "07:00:00"');


// получаем по ним данные
$nightSmena = DB('brigadir, marten, lpk, don, oplmat, prostoy, plansmena, comment', 'brigada', 'id = ' . $nightSmenaId);
$daySmena = DB('brigadir, marten, lpk, don, oplmat, prostoy, plansmena, comment', 'brigada', 'id = ' . $daySmenaId);

// информация по финансам

$revenueEvd = DBOnce('SUM(value)', 'kassa', 'type="Приход" and date ' . $bwnow);
$revenueBank = DBOnce('SUM(summa)', 'bank', '(type="Оплата от покупателя" or type="Перевод с другого счета") and date ' . $bwnow);

$revenueAll = numb($revenueEvd + $revenueBank);

$revenueImg = 'revenue';

if ($revenueAll == 0) {
    $revenueImg = 'revenue-no';
}

$rateEvd = DBOnce('SUM(value)', 'kassa', 'type="Расход" and date ' . $bwnow);
$rateBank = DBOnce('SUM(summa)', 'bank', '(type!="Оплата от покупателя" or type!="Перевод с другого счета") and date ' . $bwnow);

$rateAll = numb($rateEvd + $rateBank);

$rateImg = 'rate';

if ($rateAll == 0) {
    $rateImg = 'rate-no';
}


foreach ($nightSmena as $n) :

    $brigadirNight = $n['brigadir'];
    $martenNight = $n['marten'];
    $lpkNight = $n['lpk'];
    $donNight = $n['don'];
    $oplmatNight = $n['oplmat'];
    $allNight = $martenNight + $lpkNight + $donNight + $oplmatNight;
    $planNight = $n['plansmena'];
    $reportNight = $n['comment'];
    $prostoyNight = $n['prostoy'];
    $prostoyNightH = date("G", strtotime($prostoyNight));
    $prostoyNightM = date("i", strtotime($prostoyNight));
    $dateNight = date("d.m", strtotime($yesterday));


endforeach;

foreach ($daySmena as $n) :

    $brigadirDay = $n['brigadir'];
    $martenDay = $n['marten'];
    $lpkDay = $n['lpk'];
    $donDay = $n['don'];
    $oplmatDay = $n['oplmat'];
    $allDay = $martenDay + $lpkDay + $donDay + $oplmatDay;
    $planDay = $n['plansmena'];
    $reportDay = $n['comment'];
    $prostoyDay = $n['prostoy'];
    $prostoyDayH = date("G", strtotime($prostoyDay));
    $prostoyDayM = date("i", strtotime($prostoyDay));
    $dateDay = date("d.m", strtotime($now));

endforeach;

// виджет план
$generalPlanPercent = 0;
$summ = $allNight + $allDay;

if (!empty($planNight) or !empty($planDay)) {
    $generalPlanPercent = okr(($summ) * 100 / ($planNight + $planDay));
}

if ($generalPlanPercent >= 100) {
    $imgPlan = 'success';
    $textPlan = 'План выполнен<br>на <strong>' . $generalPlanPercent . '</strong>%';
    $textPlanSmall = 'План - выполнен на ' . $generalPlanPercent . '%';
} else {
    $imgPlan = 'fail';
    $textPlan = 'План НЕ выполнен<br>Взяли только <strong>' . $generalPlanPercent . '</strong>%';
    $textPlanSmall = 'План - НЕ выполнен. Взяли только ' . $generalPlanPercent . '%';
}

if ($generalPlanPercent == 0 and $summ == 0) {
    $imgPlan = 'fail';
    $textPlan = 'Производство<br>не работало';
    $textPlanSmall = 'План - НЕ выполнен. Производство не работало';
}

if ($generalPlanPercent == 0 and $summ > 0) {
    $imgPlan = 'success';
    $textPlan = 'Производство отработало<br>нормально. План выполнен.';
    $textPlanSmall = 'Производство отработало нормально';
}


// виджет щебень/скрап
$shebenToday = round(DBOnce2('SUM(NETTO)', 'weighing', '(GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE ' . $bwnow) / 1000);
$scrapToday = round(DBOnce2('SUM(NETTO)', 'weighing', '(GRUZ_NAME = "СКРАП 200 плюс" OR GRUZ_NAME = "СКРАП 400 плюс" OR GRUZ_NAME = "СКРАП 500-800 мм" OR GRUZ_NAME = "СКРАП 15-50" OR GRUZ_NAME = "СКРАП 200+" OR GRUZ_NAME = "СКРАП 50-400 мм") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE ' . $bwnow) / 1000);

$realAll = $shebenToday + $scrapToday;

$realImg = 'barcode';

if ($realAll == 0) {
    $realImg = 'barcode-no';
}

// механики

$techCount = 0;

$reportMech = DBOnce('report', 'tech_report', 'datetime = "' . $now . '"');


$toDone = DB('*', 'tech_work', 'status = "done" and datetime ' . $bwnow);
$toInWork = DB('*', 'tech_work', 'status = "inwork"');


if (!empty($reportMech) or !empty($toDone) or !empty($toInWork)) {
    $techCount = 1;
}

?>
<div style="display: none;font-size: 1px;color: #333333;line-height: 1px;max-height: 0px;opacity: 0;overflow: hidden">
    <?= $textPlanSmall; ?>.<br>Отгрузили <?= $shebenToday; ?>т. щебня
    и <?= $scrapToday; ?>т. СКРАПа.<br>Доход +<?= $revenueAll; ?> руб.<br>Расход -<?= $rateAll; ?> руб.<br>
    <?php if (!empty($toDone)) : ?>

        <?php foreach ($toDone as $n) : ?>

            <?php
            $typeTO = '';
            if (!empty($n['type'])) {
                $typeTO = $n['type'];
            }
            $techName = DBOnce('name', 'tech_tech', 'id=' . $n['tech']);
            ?>
            Провели ТО <?= $typeTO; ?> для <?= $techName; ?>.
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
    <tr>
        <td style="word-break:break-word;" align="center">
            <div style="vertical-align:top;text-align:left;width:100%;max-width:800px;">
                <div
                        style="padding: 20px;max-width: 600px;background-color: #ffffff;">
                    <h2 style="font-size: 20px;font-weight: 900; color:#000000;">Отчет о работе с <?=$dateNight;?> 20:00 по <?=$dateDay;?>
                        20:00</h2>

                    <table border="0" style="margin-top: 30px">
                        <tbody>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/<?= $imgPlan; ?>.jpg"/>
                            </td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px"><?= $textPlan; ?></p></td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/<?= $realImg; ?>.jpg"/>
                            </td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Отгрузили
                                    <strong><?= numb($shebenToday); ?>т.</strong> щебня
                                    и<br><strong><?= numb($scrapToday); ?>т.</strong> СКРАПа</p>
                            </td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/<?= $revenueImg; ?>.jpg"/>
                            </td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Общие поступления<br><strong>+
                                        <?= $revenueAll; ?> руб.</strong></p></td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/<?= $rateImg; ?>.jpg"/>
                            </td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Общие расходы<br><strong>-
                                        <?= $rateAll; ?> руб.</strong></p></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if (!empty($nightSmenaId)) : ?>
                        <p
                                style="margin-top: 30px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            <strong><?= $brigadirNight; ?></strong> - <?= $dateNight; ?> <span
                                    style="font-weight: 600;color: #2F80ED">(ночная)</span></p>
                        <hr style=" border: 1px solid #e8e8e8; ">

                        <p
                                style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            План <?= numb($planNight); ?>т. <?php if ($allNight >= $planNight) : ?><span
                                    style="font-weight: 600;color: #219653">выполнен</span><?php else : ?><span
                                    style="font-weight: 600;color: #EB5757">не выполнен</span><?php endif; ?>.
                            Взяли <?= numb($allNight); ?>т.</p>

                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;"><span
                                    style="font-weight: 600;color: #EB5757">М</span>: <?= $martenNight; ?>т. / <span
                                    style="font-weight: 600;color: #F2C94C">Л</span>: <?= $lpkNight; ?>т. / <span
                                    style="font-weight: 600;color: #219653">Д</span>: <?= $donNight; ?>т. / <span
                                    style="font-weight: 600;color: #2F80ED">О</span>: <?= $oplmatNight; ?>т.
                        </p>

                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #828282;text-align:left;margin-bottom: 5px;">
                            <?= $reportNight; ?> Простой <?= $prostoyNightH; ?> часов <?= $prostoyNightM; ?> минут.
                        </p>

                        <p
                                style="margin-top: 30px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            <strong><?= $brigadirDay; ?></strong> - <?= $dateDay; ?> <span
                                    style="font-weight: 600;color: #F2C94C">(дневная)</span></p>
                        <hr style=" border: 1px solid #e8e8e8; ">

                        <p
                                style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            План <?= numb($planDay); ?>т. <?php if ($allDay >= $planDay) : ?><span
                                    style="font-weight: 600;color: #219653">выполнен</span><?php else : ?><span
                                    style="font-weight: 600;color: #EB5757">не выполнен</span><?php endif; ?>.
                            Взяли <?= numb($allDay); ?>т.</p>

                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;"><span
                                    style="font-weight: 600;color: #EB5757">М</span>: <?= $martenDay; ?>т. / <span
                                    style="font-weight: 600;color: #F2C94C">Л</span>: <?= $lpkDay; ?>т. / <span
                                    style="font-weight: 600;color: #219653">Д</span>: <?= $donDay; ?>т. / <span
                                    style="font-weight: 600;color: #2F80ED">О</span>: <?= $oplmatDay; ?>т.
                        </p>

                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #828282;text-align:left;margin-bottom: 5px;">
                            <?= $reportDay; ?> Простой <?= $prostoyDayH; ?> часов <?= $prostoyDayM; ?> минут.
                        </p>

                    <?php endif; ?>
                    <?php if ($techCount != 0) : ?>
                        <div style="background: whitesmoke; padding: 20px; margin-top: 20px">

                            <h2 style="font-size: 20px;font-weight: 900; color:#000000;margin-top: 0px">Отчет
                                механиков</h2>

                            <?php if (!empty($reportMech)) : ?>
                                <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                                    <?= $reportMech; ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($toDone)) : ?>

                                <?php foreach ($toDone as $n) : ?>

                                    <?php
                                    $typeTO = '';
                                    if (!empty($n['type'])) {
                                        $typeTO = $n['type'];
                                    }
                                    $techName = DBOnce('name', 'tech_tech', 'id=' . $n['tech']);
                                    ?>
                                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                                        Провели <strong>ТО <?= $typeTO; ?></strong> для
                                        <strong><?= $techName; ?></strong>
                                    </p>
                                <?php endforeach; ?>
                            <?php endif; ?>



                            <?php if (!empty($toInWork)) : ?>

                                <?php foreach ($toInWork as $n) : ?>

                                    <?php
                                    $typeTO = '';
                                    if (!empty($n['type'])) {
                                        $typeTO = $n['type'];
                                    }
                                    $techName = DBOnce('name', 'tech_tech', 'id=' . $n['tech']);
                                    ?>
                                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                                        Готовимся к <strong>ТО <?= $typeTO; ?></strong> для
                                        <strong><?= $techName; ?></strong>
                                    </p>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
