<?php

// pdo
global $pdo;

// касса
$kassa = 'kassa2';

// datetime и путь
$url = $_SERVER['REQUEST_URI'];
$datetime = date("Y-m-d H:i:s");

// деньги в кассе
$sql = "SELECT `value` FROM `".$kassa."` WHERE `id`='1'";
$row = $pdo->query($sql);
$result = $row->fetch();
$money = $result[0];

$balance = number_format($money,0,'',' ');


if (isset($_POST["value"])) {

    $type = $_POST['type'];
    $what = $_POST['what'];
    $value = $_POST['value'];

    // вставка в таблицу кассы
    $sql = $pdo->prepare("INSERT INTO `".$kassa."` SET `type` = :type, `what` = :what,`date` = :date, `value` = :value");
    $sql->execute(array('type' => $type, 'what' => $what, 'date' => $datetime, 'value' => $value));

    // запись в лог
    $log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
    $action = 'Добавил '.$type.' денег. Сумма '.$value.'. В кассе 2 было - '.$balance.' руб.';
    $log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));

    if ($sql) {

        if ($type == 'Расход') {

            $newmoney = $money - $value;

            // обновим кассу

            $newkassa = $pdo->prepare('UPDATE `'.$kassa.'` SET value = :value where id="1"');
            $newkassa->execute(array('value' => $newmoney));

        }

        if ($type == 'Приход') {

            $newmoney = $money + $value;

            // обновим кассу

            $newkassa = $pdo->prepare('UPDATE `'.$kassa.'` SET value = :value where id="1"');
            $newkassa->execute(array('value' => $newmoney));
        }
        successmes($url);
    } else {
        errormes($url);
    }
}
?>
<div class="info-block">
    <div class="header-block">
        <h3><?php echo $balance.' руб.'; ?></h3>
    </div>
    <div class="inside">
        <form method="post">
            <div class="form-group row">
                <label for="type" class="col-sm-3 col-form-label">Тип операции</label>
                <div class="col-sm-9">
                    <select name="type" class="form-control">
                        <option value="Расход">Расход</option>
                        <option value="Приход">Приход</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="value" class="col-sm-3 col-form-label">Сумма</label>
                <div class="col-sm-9">
                    <input type="number" value="" class="form-control" name="value" placeholder="Сумма, руб." required>
                </div>
            </div>
            <div class="form-group row">
                <label for="what" class="col-sm-3 col-form-label">Комментарий</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="what" placeholder="Коммент" required></textarea>
                </div>
            </div>
            <button type="submit" name="Submit" class="btn btn-primary">Добавить данные</button>
        </form>
    </div>
</div>

<?php
if (isset($_POST['del_id'])) { //проверяем, есть ли переменная

// datetime и путь
    $url = '/kassad/';

    $delid = $_POST['del_id'];


    $sql = "SELECT * FROM `".$kassa."` WHERE id='".$delid."'";
    $tech = $pdo->prepare($sql);
    $tech->execute();
    $techs = $tech->fetchAll(PDO::FETCH_BOTH);

    foreach ($techs as $n) {
        $ktype = $n['type'];
        $kwhat = $n['what'];
        $kdate = $n['date'];
        $kvalue = $n['value'];
    }
    if ($kvalue > 0) {

        if ($ktype == 'Расход') {
            $newmoneykassa = $money + $kvalue;
        }
        if ($ktype == 'Приход') {
            $newmoneykassa = $money - $kvalue;
        }
        // обновим кассу

        $newkassa = $pdo->prepare('UPDATE `'.$kassa.'` SET value = :value WHERE id="1"');
        $newkassa->execute(array('value' => $newmoneykassa));

        $deletesql = $pdo->prepare('DELETE from `'.$kassa.'` WHERE `id` = :id');
        $deletesql->execute(array('id' => $delid));

        // запись в лог
        $log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime, `imp` = '1'");
        $action = 'Удалил '.$ktype.' денег. Сумма '.$kvalue.'. В кассе 2 было - '.$balance.' руб.';
        $log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));

        if ($deletesql) {
            successmes($url);
        } else {
            errormes($url);
        }
    } else {

        // если id не найден -> перезагружаем страницу
        header("location:".$url);
    }
}


?>
<div class="info-block">
    <div class="inside">
        <table class="table table-hover table-striped w-100">
            <thead>
            <tr>
                <th scope="col">Сумма</th>
                <th scope="col">Дата</th>
                <th scope="col"><i class="fas fa-trash-alt"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sqlkassa = "SELECT id, type, value, what, date FROM ".$kassa." ORDER BY date DESC LIMIT 50";
            $tablekass = $pdo->prepare($sqlkassa);
            $tablekass->execute();
            $tablekass = $tablekass->fetchAll(PDO::FETCH_BOTH);
            foreach ($tablekass as $n) {
                $newDate2 = date("d.m", strtotime($n['date']));
                $value = number_format($n['value'],0,'',' ');

                if ($n['type'] == 'Приход') {
                    $summa = '<span class="text-success"><strong>+'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
                }

                if ($n['type'] == 'Расход') {
                    $summa = '<span class="text-danger"><strong>-'.$value.' <i class="fas fa-ruble-sign"></i></strong></span>';
                }


                echo '<tr><td><p>'.$summa.'</p><p class="whattb">'.$n['what'].'</p></td><td>'.$newDate2.'</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$n['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>