<?php
//include '../../../bdcon.php';
global $pdo;
global $pdoves;
global $now;
global $yesterday;

// заготовки
//$now = '2019-08-22';
//$yesterday = '2019-08-21';
$bwnow = 'between "'.$yesterday.' 20:00:00" and "'.$now.' 19:59:59"';

// информация по сменам

// определяем id ночной и дневной смены
$nightSmenaId = DBOnce('id','brigada','datestart = "'.$yesterday.'" and timestart = "19:00:00"');
$daySmenaId = DBOnce('id','brigada','datestart = "'.$now.'" and timestart = "07:00:00"');


// получаем по ним данные
$nightSmena = DB('brigadir, marten, lpk, don, oplmat, prostoy, plansmena, comment','brigada','id = ' . $nightSmenaId);
$daySmena = DB('brigadir, marten, lpk, don, oplmat, prostoy, plansmena, comment','brigada','id = ' . $daySmenaId);


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
    $dateDay = date("d.m", strtotime($now));

endforeach;

// виджет план
$generalPlanPercent = okr(($allNight + $allDay) * 100/($planNight + $planDay));

if ($generalPlanPercent >= 100) {
    $imgPlan = 'success';
    $textPlan = 'План выполнен<br>на <strong>' . $generalPlanPercent . '</strong>%';
    $textPlanSmall = 'План - выполнен на ' . $generalPlanPercent . '%';
} else {
    $imgPlan = 'fail';
    $textPlan = 'План НЕ выполнен<br>Взяли только <strong>' . $generalPlanPercent . '</strong>%';
    $textPlanSmall = 'План - НЕ выполнен. Взяли только ' . $generalPlanPercent . '%';
}

if ($generalPlanPercent == 0) {
    $imgPlan = 'fail';
    $textPlan = 'Производство<br>не работало';
    $textPlanSmall = 'План - НЕ выполнен. Производство не работало';
}

// виджет щебень/скрап
$shebenToday = round(DBOnce2('SUM(NETTO)', 'weighing', '(GRUZ_NAME = "Песок шлаковый 0-5 мм" OR GRUZ_NAME = "Щебень 5-20 мм" OR GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE ' . $bwnow) / 1000);
$scrapToday = round(DBOnce2('SUM(NETTO)', 'weighing', '(GRUZ_NAME = "СКРАП 200 плюс" OR GRUZ_NAME = "СКРАП 400 плюс" OR GRUZ_NAME = "СКРАП 500-800 мм" OR GRUZ_NAME = "СКРАП 15-50" OR GRUZ_NAME = "СКРАП 200+" OR GRUZ_NAME = "СКРАП 50-400 мм") and TYP_EVENT="Реализация (отгрузка покупателю)" and DATETIME_CREATE ' . $bwnow) / 1000);


?>

<div style="display: none;font-size: 1px;color: #333333;line-height: 1px;max-height: 0px;opacity: 0;overflow: hidden">
    План - выполнен на 100%. Отгрузили 123т. щебня и 144т. СКРАПа. Доход +1.231.213 руб. Расход -3.234.123 руб. Провели
    ТО для CAT 320
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
    <tr>
        <td style="word-break:break-word;" align="center">
            <div style="vertical-align:top;text-align:left;width:100%;max-width:800px;">
                <div
                        style="padding: 20px;max-width: 600px;background-color: #ffffff;">
                    <h2 style="font-size: 20px;font-weight: 900; color:#000000;">Отчет о работе с 11.09 20:00 по 12.09
                        20:00</h2>

                    <table border="0" style="margin-top: 30px">
                        <tbody>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/<?= $imgPlan; ?>.jpg"/></td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px"><?= $textPlan; ?></p></td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/barcode.jpg"/></td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Отгрузили
                                    <strong><?= numb($shebenToday); ?>т.</strong> щебня и<br><strong><?= numb($scrapToday); ?>т.</strong> СКРАПа</p>
                            </td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/revenue.jpg"/></td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Общие поступления<br><strong>+
                                        1.324.234 руб.</strong></p></td>
                        </tr>
                        <tr>
                            <td height="80px" valign="top"><img width="50px" style="margin-right: 20px;margin-top: 3px"
                                                                src="https://rubezh-info.ru/images/rate.jpg"/></td>
                            <td valign="top"><p style="line-height: 1.8; margin-top:0px">Общие расходы<br><strong>-
                                        2.234.234 руб.</strong></p></td>
                        </tr>
                        </tbody>
                    </table>

                    <p
                            style="margin-top: 30px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                        <strong><?= $brigadirNight; ?></strong> - <?= $dateNight; ?> <span
                                style="font-weight: 600;color: #2F80ED">(ночная)</span></p>
                    <hr style=" border: 1px solid #e8e8e8; ">

                    <p
                            style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                        План <?= numb($planNight); ?>т. <?php if ($allNight >= $planNight) : ?><span style="font-weight: 600;color: #219653">выполнен</span><?php else : ?><span style="font-weight: 600;color: #EB5757">не выполнен</span><?php endif; ?>. Взяли <?= numb($allNight); ?>т.</p>

                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;"><span
                                style="font-weight: 600;color: #EB5757">М</span>: <?= $martenNight; ?>т. / <span
                                style="font-weight: 600;color: #F2C94C">Л</span>: <?= $lpkNight; ?>т. / <span
                                style="font-weight: 600;color: #219653">Д</span>: <?= $donNight; ?>т. / <span
                                style="font-weight: 600;color: #2F80ED">О</span>: <?= $oplmatNight; ?>т.
                    </p>

                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #828282;text-align:left;margin-bottom: 5px;">
                        <?= $reportNight;?> Простой <?= $prostoyNightH; ?> часов <?= $prostoyNightM; ?> минут.
                    </p>

                    <p
                            style="margin-top: 30px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                        <strong><?= $brigadirDay; ?></strong> - <?= $dateDay; ?> <span
                                style="font-weight: 600;color: #F2C94C">(дневная)</span></p>
                    <hr style=" border: 1px solid #e8e8e8; ">

                    <p
                            style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                        План <?= numb($planDay); ?>т. <?php if ($allDay >= $planDay) : ?><span style="font-weight: 600;color: #219653">выполнен</span><?php else : ?><span style="font-weight: 600;color: #EB5757">не выполнен</span><?php endif; ?>. Взяли <?= numb($allDay); ?>т.</p>

                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;"><span
                                style="font-weight: 600;color: #EB5757">М</span>: <?= $martenDay; ?>т. / <span
                                style="font-weight: 600;color: #F2C94C">Л</span>: <?= $lpkDay; ?>т. / <span
                                style="font-weight: 600;color: #219653">Д</span>: <?= $donDay; ?>т. / <span
                                style="font-weight: 600;color: #2F80ED">О</span>: <?= $oplmatDay; ?>т.
                    </p>

                    <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #828282;text-align:left;margin-bottom: 5px;">
                        <?= $reportDay;?> Простой <?= $prostoyDay; ?> часов.
                    </p>

                    <div style="background: whitesmoke; padding: 20px; margin-top: 20px">
                        <h2 style="font-size: 20px;font-weight: 900; color:#000000;margin-top: 0px">Отчет механиков</h2>
                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            SENNEBOGEN 830 переборка гидронасоса. сварка стелажа для бочек с отработкой.
                        </p>
                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            Провели <strong>ТО 500</strong> для <strong>CAT 320</strong>
                        </p>
                        <p style="margin-top: 0px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;margin-bottom: 5px;">
                            Готовимся к <strong>ТО 500</strong> для <strong>CAT 330</strong>
                        </p>
                    </div>

                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
