<?php
global $pdoves;
$date = date('Y-m-d');
$toplivo = DBOnce('SUM(toplivo)', 'gsm', 'date = "' . $date . '"');

$vyborka = "TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '" . $date . " 00:00:00' and '" . $date . " 23:59:59' and GRUZ_NAME = 'Щебень 20-40 мм.'";
$fullDataQuery = $pdoves->prepare("SELECT  FIRMA_POL, SUM(NETTO) AS SUM_NETTO FROM weighing WHERE " . $vyborka . " GROUP BY FIRMA_POL");
$fullDataQuery->execute();
$fullData = $fullDataQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: none;font-size: 1px;color: #333333;line-height: 1px;max-height: 0px;opacity: 0;overflow: hidden">
    Отчет по топливу и щебню за <?= $date ?>
</div>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tbody>
    <tr>
        <td style="word-break:break-word;" align="center">
            <div style="vertical-align:top;text-align:left;width:100%;max-width:600px;">
                <div align="center" class="block center" style="padding: 20px;text-align: center;max-width: 600px;background-color: #ffffff;">
                    <h2 style="font-size: 20px;font-weight: 900; color:#000000;">Отчет по топливу за <?= $date ?></h2>
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;">Топлива потрачено: <?= $toplivo ?> литров.</p>
                    <h2 style="font-size: 20px;font-weight: 900; color:#000000;">Отчет по щебню 20-40 за <?= $date ?></h2>
                    <ul>
                    <?php $sum = 0;?>
                    <?php foreach ($fullData as $row):
                        $sum += $row['SUM_NETTO'];
                    ?>
                    <li class="margin20t" style="margin-top: 20px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;"><?= ($row['FIRMA_POL'] == '') ? 'Ж/Д' : $row['FIRMA_POL'] ?> - <?= $row['SUM_NETTO'] / 1000 ?> т</li>
                    <?php endforeach; ?>
                    </ul>
                    <p class="margin20t" style="margin-top: 20px;line-height: 2;font-size: 16px;color: #353b41;text-align:left;">Итого отгружено: <?= $sum / 1000 ?> т</p>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
</table>
