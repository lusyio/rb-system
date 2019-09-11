<?php

function arrayzap($array) {
    $results = [];
    $data    = explode("/", $array);
    foreach ($data as $row) {
        $line = explode(":", $row);
        $results[] = [$line[0], $line[1]];
    }
    return $results;
}
$zaplist = DB('*', 'tech_oil', 'tech=' . $tech);
$oils = DB('*', 'tech_oil', 'type="Масло"');
if (isset($_POST['toId'])) {
    $tofortech = DBOnce('zap', 'tech_to', 'id=' . $_POST['toId']);
    $arrayzap = arrayzap($tofortech);
} else {
    $arrayzap = [[0,0]];
}
if (!empty($arrayzap)):
    foreach ($arrayzap as $row): ?>
<div class="row mb-3 zapBlockInEdit zap-row">
    <div class="col-sm-8">
        <select name="zaplist" class="form-control mb-1 zaplist">
        <?php foreach ($zaplist as $n): ?>
            <option value="<?=$n['id']?>"<?= ($n['id'] == $row[0]) ? ' selected' : '' ?>><?=$n['name']?></option>
        <?php endforeach; ?>
        <?php foreach ($oils as $n): ?>
            <option value="<?=$n['id']?>"<?= ($n['id'] == $row[0]) ? ' selected' : '' ?>><?=$n['name']?></option>
        <?php endforeach; ?>
        </select>
        <small class="text-secondary">Запчасть</small>
    </div>
    <div class="col">
        <input class="form-control mb-1 countzap" type="number" value="<?=$row[1] ?>" placeholder="Н-р: 5"/>
        <small class="text-secondary">Кол-во (шт. или л.)</small>
    </div>
    <div class="col-sm-1 pl-1">
        <button class="btn btn-outline-secondary form-control pl-0 pr-0 remove-row"><i class="far fa-trash-alt"></i></button>
    </div>
</div>
<?php
    endforeach;
endif;
