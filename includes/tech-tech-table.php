<div class="card-body">
    <?php
    $normcount = DBOnce('COUNT(*) as count','tech_norm','tech='.$idtech . ' AND datetime >= "' . $startDate . '" AND datetime <= "' . $endDate . '"');
    if (empty($normcount)) {?>
        <hr><p class="text-center mt-5 mb-5">Данных по данной технике за период <?= date('d.m', strtotime($startDate)) ?> - <?= date('d.m', strtotime($endDate)) ?> нет</p>
    <?php } else {
        $norm = DB('*','tech_norm','tech='.$idtech. ' AND datetime >= "' . $startDate . '" AND datetime <= "' . $endDate . '" order by datetime DESC');
        $narabotka = DB('(MAX(motchas) - MIN(motchas)) AS narabotka','tech_norm','tech='.$idtech. ' AND datetime >= "' . $startDate . '" AND datetime <= "' . $endDate . '" order by datetime DESC');

        $daysInPeriod = ((strtotime($endDate) - strtotime($startDate)) / (3600 * 24)) + 1;
        ?>
        <div>
            <p><b>За период <?= date('d.m', strtotime($startDate)) ?> - <?= date('d.m', strtotime($endDate)) ?>:</b> наработка <?= $narabotka[0]['narabotka'] ?> моточасов, средняя дневная наработка - <?= round($narabotka[0]['narabotka'] / $daysInPeriod) ?> моточасов.</p>
        </div>
        <table class="table mb-0">
            <thead>
            <tr class="table-secondary">
                <th scope="col" class="pl-4">Моточасы</th>
                <th scope="col">Остаток топлива</th>
                <th scope="col">Дата</th>
                <th scope="col"><i class="fas fa-trash-alt"></i></th>
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
                    <td width="50px">
                        <form method="post">
                            <input value="<?=$n['id']?>" name="delmot" hidden/>
                            <button type="submit" class="delnorm btn btn-link p-0" ><i class="fas fa-trash-alt text-danger"></i></button>
                        </form>
                    </td>
                </tr>

            <?php	} ?>
            </tbody>
        </table>
    <?php } ?>
</div>