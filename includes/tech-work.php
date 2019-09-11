<?php
global $pdo;
if (isset($_POST["addtech"])) {
    $name = $_POST["addtech"];
    // вставка в таблицу техники

    $sql = $pdo->prepare("INSERT INTO `tech_tech` SET `name` = :name");
    $sql->execute(array('name' => $name));
    if ($sql) {
        successmes($url);
    } else {
        errormes($url);
    }
}

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная

    $url = '/tech-work/';
    $iddel = $_POST['del_id'];
    $deletesql = $pdo->prepare('DELETE from `tech_tech` WHERE `id` = :id');
    $deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
        successmes($url);
    } else {
        errormes($url);
    }

}

if (isset($_POST['typeto'])) { //проверяем, есть ли переменная

    $toid = $_POST['toid'];
    $typeto = $_POST['typeto'];

    $updatework = 'UPDATE tech_work SET type = "' . $typeto . '" WHERE id = "' . $toid . '"';
    $updatework = $pdo->prepare($updatework);
    $updatework->execute();

    if ($updatework) {
        successmes($url);
    } else {
        errormes($url);
    }

}

if (isset($_POST['motchas'])) { //проверяем, есть ли переменная

    $techid = $_POST['tech'];
    $motchas = $_POST['motchas'];
    $toplivo = $_POST['toplivo'];
    $sql = $pdo->prepare("INSERT INTO `tech_norm` SET `motchas` = :motchas, `toplivo` = :toplivo, `tech` = :tech, `datetime` = :datetime");
    $sql->execute(array('motchas' => $motchas, 'toplivo' => $toplivo, 'tech' => $techid, 'datetime' => $datetime));
    if ($sql) {
        successmes($url);
    } else {
        errormes($url);
    }

}

if (isset($_POST["work"])) {

    $work = $_POST["work"];

    $techTO = DBOnce('tech', 'tech_work', 'id=' . $work);
    $typeTO = DBOnce('type', 'tech_work', 'id=' . $work);

    $techmtc = DBOnce('motchas', 'tech_norm', 'tech=' . $techTO . ' order by datetime DESC');

    if (!empty($typeTO)) {

        $zapList = DBOnce('zap', 'tech_to', 'tech=' . $techTO . ' and type="' . $typeTO . '"');

        $arrayzap = arrayzap($zapList);
        foreach ($arrayzap as $n => $i) {
            $c = 0;
            foreach ($i as $pr => $pp) {
                if ($c == 0) {
                    $idzap = DBOnce('id', 'tech_oil', 'id=' . $pp);
                } else {
                    $countto = $pp;

                    $countSclad = DBOnce('count', 'tech_oil', 'id=' . $idzap);
                    $newcount = $countSclad - $countto;

                    $updatework = 'UPDATE tech_oil SET count = "' . $newcount . '" WHERE id = "' . $idzap . '"';

                    $updatework = $pdo->prepare($updatework);
                    $updatework->execute();

                }

                $c++;
            }
        }
    }
    $updatework = 'UPDATE tech_work SET status = "done", motchas = "' . $techmtc . '", datetime = "' . $datetime . '" WHERE id = "' . $work . '"';
    $updatework = $pdo->prepare($updatework);
    $updatework->execute();


    $idtech = DBOnce('tech', 'tech_work', 'id=' . $work);
    $nametech = DBOnce('name', 'tech_tech', 'id=' . $idtech);
    $log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
    $action = 'Завершил ТО для ' . $nametech;
    $log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
    if ($updatework) {
        successmes($url);
    } else {
        errormes($url);
    }
}
// вывод техники
$tech = DB('*', 'tech_tech', '');

function arrayzap($array)
{
    $results = [];
    $data = explode("/", $array);
    foreach ($data as $row) {
        $line = explode(":", $row);
        $results[] = [$line[0], $line[1]];
    }
    return $results;
}

$zap[] = array("id" => 1, "count" => 15);
$zap[] = array("id" => 3, "count" => 30);

/*
echo 'Изначальный массив';
foreach ($zap as $n => $i)
  {
  echo  "<br>$n:<br>";
  foreach ($i as $pr => $pp)
    {
    echo  "$pr = $pp<br>";
    }
  }

function convert_multi_array($array) {
  $out = implode("/",array_map(function($a) {return implode(":",$a);},$array));
  return $out;
}

$newzap = convert_multi_array($zap);

global $pdo;
$updatework = 'UPDATE tech_work SET zap = "'.$newzap.'" WHERE id = 82';
$updatework = $pdo->prepare($updatework);
$updatework->execute();
*/


?>
<div id="tech">
    <div class="mt-2" id="techButtonBlock">
        <button class="btn btn-light bg-white border mr-2" data-toggle="collapse" data-target="#addToplivo"
                aria-expanded="false" aria-controls="addToplivo"><i class="fas fa-tachometer-alt mr-3"></i>Внести
            показания топлива
        </button>
        <a href="/tech-sclad/" class="btn btn-light bg-white border mr-2"><i class="fas fa-tools mr-3"></i>Склад
            запчастей</a>
        <a href="/tech-to/" class="btn btn-light bg-white border mr-2"><i class="fas fa-clone mr-3"></i>Управление
            ТО</a>
        <button class="btn btn-light bg-white border mr-2" data-toggle="collapse" data-target="#addTech"
                aria-expanded="false" aria-controls="addTech"><i class="fas fa-plus mr-3"></i>Добавить технику
        </button>
    </div>
</div>

<!-- коллапс на добавление техники -->
<div class="collapse" id="addTech">

    <div class="card mt-3">
        <div class="card-body">
            <form method="post" class="form-inline pl-4 mb-0 mt-2">
                <div class="form-group mb-2">
                    <label for="staticEmail2" class="sr-only">Наименование</label>
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Наименование">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="addtech" class="sr-only">Tech</label>
                    <input type="text" class="form-control" name="addtech" id="addtech" placeholder="Н-р: CAT 320"
                           autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Добавить</button>
            </form>
        </div>
    </div>


</div>

<!-- коллапс на добавление техники -->

<!-- коллапс на добавление данных по топливу -->
<div class="collapse" id="addToplivo">

    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <form method="post">
                        <div class="form-group mb-2">
                            <label for="staticEmail2">Техника</label>
                            <select name="tech" id="techS" class="form-control">
                                <?php foreach ($tech as $n) { ?>
                                    <option value="<?= $n['id'] ?>"><?= $n['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="staticEmail2">Остаток топлива</label>
                            <input type="number" name="toplivo" min="0" max="9999999999" class="form-control"
                                   placeholder="Топливо в литрах" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="staticEmail2">Моточасы</label>
                            <input type="number" name="motchas" min="0" max="9999999999" class="form-control"
                                   placeholder="Моточасы" autocomplete="off" required>
                        </div>
                        <button id="addpok" class="btn btn-success mr-3">Добавить <i class="fas fa-check"></i></button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <div id="history">
                        <?php
                        $normcount = DBOnce('COUNT(*) as count', 'tech_norm', 'tech=41');
                        if (empty($normcount)) {
                            echo '<hr><p class="text-center mt-5 mb-5">Данных по данной технике нет</p>';
                        } else {
                            $norm = DB('*', 'tech_norm', 'tech=41 order by datetime DESC limit 5');
                            ?>
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

                                    <tr id="norm-<?= $n['id'] ?>">
                                        <td class="pl-4 font-weight-bold"><?= $n['motchas'] ?></td>
                                        <td class="text-muted"><?= $n['toplivo'] ?></td>
                                        <td class="text-muted"><?= $date ?></td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- коллапс на добавление данных по топливу -->

<hr>


<?php

$tech = DB('*', 'tech_tech', '');

if (!empty($tech)) { ?>

<div class="row" id="techToBlock">

    <?php
    $i = 1;
    foreach ($tech as $n) {
        $tech = $n['id'];
        $to = DBOnce('motchas', 'tech_work', 'status="inwork" and tech=' . $n['id']);
        $typeTO = '';
        $typeTO = DBOnce('type', 'tech_work', 'status="inwork" and tech=' . $n['id']);
        if (!empty($to)) {
            $idto = DBOnce('id', 'tech_work', 'status="inwork" and tech=' . $n['id']);

            $mch = DBOnce('motchas', 'tech_norm', 'tech=' . $n['id'] . ' order by datetime DESC');

            $interval = 500;
            $otschet = 0;

            if ($tech == 41 or $tech == 42) {
                $interval = 500;
            }
            if ($tech == 41) {
                $otschet = 8700;
            }
            if ($tech == 42) {
                $otschet = 5500;
            }
            if ($tech == 46) {
                $otschet = 25267;
            }

            $new = (okr(($mch - $otschet) / $interval) * $interval) - ($mch - $otschet);

            if (!empty($to)) {

                if ($new >= 31 and $new <= 100) {
                    $new = '<span class="text-warning font-weight-bold">Скоро ТО ' . $typeTO . '</span>';
                    $todone = '<button class="btn btn-secondary btn-sm" disabled>Подготовка</button>';
                } else if ($new <= 30) {
                    $new = '<span class="text-danger font-weight-bold">ТО ' . $typeTO . '</span>';
                    $todone = '<form method="post" class="mb-0"><input class="hidden" name="work" value="' . DBOnce('id', 'tech_work', 'tech=' . $n['id'] . ' order by datetime DESC') . '"><button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-check mr-2"></i>Завершить ТО</button></form>';
                }

            } else {
                $new = '';
            }

            if ($mch > 0) {
                $mch = '<span style="white-space: nowrap;">' . numb($mch) . ' Мч</span>';
            } else {
                $mch = '<span class="text-muted">н.з.</span>';
            }
            $arrayzap = '';
            $zap = DBOnce('zap', 'tech_to', 'tech=' . $tech . ' and type="' . $typeTO . '"');
            if (!empty($zap)) {
                $arrayzap = arrayzap($zap);
            }
            ?>
            <div class="col-sm-4">
                <div class="w-100 card mb-3 p-0" style="white-space: normal">
                    <div class="card-body p-1">
                        <p class="mt-2 mb-0 text-center"><?= $new ?></p>
                        <hr class="mb-0">
                        <span style="white-space: nowrap;position: absolute;right: 4px;top: 1px;color: #f0f0f0;font-size: 10px;">#<?= $n['id'] ?></span>
                        <?php
                        $filename = 'images/' . $n['id'] . '.jpg';

                        if (file_exists($filename)) {
                            $imgname = $n['id'];
                        } else {
                            $imgname = 'no-image';
                        }
                        ?>
                        <div class="row pl-5 pr-3 pt-2 pb-2">
                            <div class="col-4">
                                <img class="w-100" src="/images/<?= $imgname ?>.jpg">
                            </div>
                            <div class="col-7 text-left">
                                <p class="text-dark mt-3 font-weight-bold mb-1"><?= $n['name'] ?></p>
                                <p class="mb-2">
                                    <small class="text-secondary"><?= $mch ?></small>
                                </p>

                            </div>
                        </div>
                        <div class="card-body pb-0 pt-2">

                            <?php
                            if (!empty($arrayzap)) {
                                $zapDeficit = false;
                                foreach ($arrayzap as $n => $i) {
                                    $c = 0;
                                    foreach ($i as $pr => $pp) {
                                        if ($c == 0) {
                                            $countzap = DBOnce('count', 'tech_oil', 'id=' . $pp);
                                            if (empty($countzap)) {
                                                $countzap = 0;
                                            }
                                        } else {
                                            $razn = $countzap - $pp;
                                            if ($countzap - $pp < 0) {
                                                $zapDeficit = true;
                                                break 2;
                                            }
                                        }
                                        $c++;
                                    }
                                }
                                ?>
                                <a class="btn btn-link text-center w-100 pb-3 <?= ($zapDeficit) ? 'text-danger' : '' ?>"
                                   data-toggle="collapse" href="#collapseToZap<?= $n['id'] ?>" role="button"
                                   aria-expanded="false" aria-controls="collapseToZap<?= $n['id'] ?>">- Список запчастей
                                    -</a>
                                <div class="collapse" id="collapseToZap<?= $n['id'] ?>">

                                    <table class="table small table-hover border-bottom">
                                        <tbody>
                                        <?php
                                        foreach ($arrayzap as $n => $i) {
                                            //	  echo  "<br>$n:<br>";
                                            $c = 0;
                                            foreach ($i as $pr => $pp) {
                                                if ($c == 0) {
                                                    $namezap = DBOnce('name', 'tech_oil', 'id=' . $pp);
                                                    echo '<tr><td>' . $namezap . '</td>';
                                                    $countzap = DBOnce('count', 'tech_oil', 'id=' . $pp);
                                                    if (empty($countzap)) {
                                                        $countzap = 0;
                                                    }
                                                    $typezap = DBOnce('type', 'tech_oil', 'id=' . $pp);
                                                    if ($typezap == 'Масло') {
                                                        $edin = ' л';
                                                    } else {
                                                        $edin = ' шт';
                                                    }
                                                } else {
                                                    $razn = $countzap - $pp;
                                                    if ($razn < 0) {
                                                        $textcolor = 'danger';
                                                    } else {
                                                        $textcolor = 'success';
                                                    }
                                                    echo '<td style="white-space: pre">' . $pp . $edin . '. (<span title="Количество на складу" class="text-' . $textcolor . '">' . $countzap . '</span>)</td>';
                                                }

                                                $c++;
                                            }

                                            echo '</tr>';
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>

                            <?php }


                            ?>


                            <?php
                            $allto = DB('*', 'tech_to', 'tech=' . $n['id']);

                            if (!empty($allto)) :
                                ?>
                                <p>Какое это ТО?</p>
                                <form method="post">
                                    <input name="toid" value="<?= $idto ?>" hidden/>
                                    <select name="typeto" class="form-control">
                                        <?php foreach ($allto as $t) : ?>
                                            <option value="<?= $t['type'] ?>"><?= $t['type'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary mt-2 w-100" type="submit">Установить тип ТО</button>
                                </form>
                                <hr>
                            <?php endif; ?>


                        </div>
                        <div class="text-center mb-3"><?= $todone ?></div>

                        <span style=" position: absolute; top: 2px; right: 0; left: 0; "></span>
                    </div>
                </div>
            </div>
            <?php $i++;
        } ?>


        <?php
    }
    } ?>

</div>


<div class="mt-3" id="techNotToBlock">
    <hr>
    <?php

    $tech = DB('*', 'tech_tech', '');

    if (!empty($tech)) { ?>


    <div class="row mr-0">

        <?php
        $i = 1;
        foreach ($tech as $n) {

            $idto = DBOnce('id', 'tech_work', 'status="inwork" and tech=' . $n['id']);

            $mch = DBOnce('motchas', 'tech_norm', 'tech=' . $n['id'] . ' order by datetime DESC');

            $interval = 500;
            $otschet = 0;

            if ($n['id'] == 41 or $n['id'] == 42) {
                $interval = 500;
            }
            if ($n['id'] == 41) {
                $otschet = 8700;
            }
            if ($n['id'] == 42) {
                $otschet = 5500;
            }
            if ($n['id'] == 46) {
                $otschet = 25267;
            }

            $new = (okr(($mch - $otschet) / $interval + 1) * $interval) - ($mch - $otschet);

            // Прогноз следующей даты ТО на основании последних $period дней - не меньше 3х!
            $period = 30;
            $startDate = date('Y-m-d', strtotime('midnight -' . $period . ' days'));
            $thirdOfPeriod = round($period / 3);
            $startDateThird = date('Y-m-d', strtotime('midnight -' . $thirdOfPeriod . ' days'));
            $endDate = date('Y-m-d');
            $narabotkaZaPeriod = DBOnce('(MAX(motchas) - MIN(motchas))', 'tech_norm', 'tech=' . $n['id'] . ' AND datetime >= "' . $startDate . '" AND datetime <= "' . $endDate . '" order by datetime DESC');
            $narabotkaThird = DBOnce('(MAX(motchas) - MIN(motchas))', 'tech_norm', 'tech=' . $n['id'] . ' AND datetime >= "' . $startDate . '" AND datetime <= "' . $endDate . '" order by datetime DESC');
            $mchSredDenPeriod = $narabotkaZaPeriod / $period;
            $mchSredDenThird = $narabotkaZaPeriod / $thirdOfPeriod;
            $mchSredDen = ($mchSredDenThird * 2 + $mchSredDenPeriod) / 3;
            if ($mchSredDen > 0) {
                $dneyDoTo = floor($new / $mchSredDen);
            } else {
                $dneyDoTo = -1;
            }

            if ($mch > 0) {
                $mchHTML = '<span style="white-space: nowrap;">' . numb($mch) . ' Мч</span>';
            } else {
                $mchHTML = '<span class="text-muted">н.з.</span>';
            }
            ?>
            <div class="col-sm-3">
                <form action="/tech-tech/" method="post">
                    <input value="<?= $n['id'] ?>" name="idtech" hidden/>
                    <button type="submit" class="w-100 card btn p-0" style="white-space: normal;overflow: hidden;">
                        <div class="card-body p-1">
                            <span style="white-space: nowrap;position: absolute;right: 4px;top: 1px;color: #f0f0f0;font-size: 10px;">#<?= $n['id'] ?></span>
                            <?php
                            $filename = 'images/' . $n['id'] . '.jpg';

                            if (file_exists($filename)) {
                                $imgname = $n['id'];
                            } else {
                                $imgname = 'no-image';
                            }
                            ?>
                            <div class="row">
                                <div class="col-3 pr-0">
                                    <img class="w-100" src="/images/<?= $imgname ?>.jpg">
                                </div>
                                <div class="col-9 text-left">
                                    <p class="text-dark mt-2 font-weight-bold mb-1"><?= $n['name'] ?></p>
                                    <p class="mb-0">
                                        <small class="text-secondary"><?= $mchHTML ?></small>
                                    </p>
                                </div>
                            </div>
                            <div class="row text-left">
                                <div class="col-12">
                                </div>
                            </div>


                            <span style=" position: absolute; top: 2px; right: 0; left: 0; "></span>
                        </div>
                        <div class="card-footer w-100 p-0 text-left pl-3 pt-1 pb-1">
                            <?php if ($mch > 0): ?>
                                <small class="text-secondary">До ТО: <?= $new ?>
                                    Мч <?= ($dneyDoTo > 0 && $dneyDoTo < 60) ? '(~' . $dneyDoTo . ' д.)' : '' ?></small>
                            <?php else: ?>
                                <small class="text-secondary">н.з.</small>
                            <?php endif; ?>

                        </div>
                    </button>
                </form>
            </div>
            <?php $i++;
        }
        } ?>
    </div>
</div>


<div class="card mt-3">
    <div class="card-header"><h5 class="mb-0">Журнал</h5></div>
    <div class="card-body">
        <table class="table mb-0 table-hover">
            <thead>
            <tr>
                <th scope="col">Техника</th>
                <th scope="col">Мот.ч.</th>
                <th scope="col">Топ-во</th>
                <th scope="col">Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $norm = DB('*', 'tech_norm', 'id!=0 order by datetime DESC limit 50');


            foreach ($norm as $n) {

                $date = date("d.m", strtotime($n['datetime']));
                $tech = DBOnce('name', 'tech_tech', 'id=' . $n['tech']);
                ?>
                <tr>
                    <td class="font-weight-bold"><?= $tech ?></td>
                    <td><?= numb($n['motchas']) ?></td>
                    <td><?= $n['toplivo'] ?></td>
                    <td><?= $date ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
        $('#techS').bind('keyup change click', function () {
            value = $('#techS :selected').val();
            addSelect(value);
        });

        function addSelect(id) {
            id = $('#techS').val();
            $.post("/ajax.php", {technorm: id, info: 'tech-history'}, controlUpdate);

            function controlUpdate(data) {
                $("#history").empty().html(data);
            }
        }
    });
</script>
<script src="/js/tech.js"></script>