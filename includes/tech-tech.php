<?php
if (isset($_POST["idtech"])) {
	$idtech = $_POST["idtech"];
	$filename = 'images/'.$idtech.'.jpg';
	if (file_exists($filename)) {
		$imgname = $idtech;
	} else {
		$imgname = 'no-image';
	} 
	$nameTech = DBOnce('name','tech_tech','id='.$idtech);
	$mch = DBOnce('motchas','tech_norm','tech='.$idtech.' order by datetime DESC');
	if ($mch > 0) {
		$mch = '<span style="white-space: nowrap;">' . numb($mch) . ' Мч</span>';
	} else {
		$mch = '<span class="text-muted">н.з.</span>';
	}
}
if (isset($_POST['delmot'])) { //проверяем, есть ли переменная
	$iddel = $_POST['delmot'];
	$url = '/tech-work/';
	$deletesql = $pdo->prepare('DELETE from `tech_norm` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}
?>
<?php if (!empty($idtech)) : ?>
<?php $startDate = date('Y-m-d', strtotime('midnight -30 days')) ?>
<?php $endDate = date('Y-m-d') ?>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-10">
				<h4 class="font-weight-bold"><?=$nameTech?></h4>
				<small class="text-secondary"><?=$mch?></small>
			</div>
			<div class="col-2">
				<img src="/images/<?=$imgname?>.jpg" style=" position: absolute; top: -20px; right: 0; height: 95px; ">
			</div>
		</div>
	</div>
</div>

<div class="d-none mt-2">
	<button class="btn btn-light bg-white border mr-2"><i class="fas fa-tachometer-alt mr-3"></i>Внести показания топлива</button>
	<button class="btn btn-light bg-white border mr-2"><i class="fas fa-oil-can mr-3"></i>Учет масла</button>
	<button class="btn btn-light bg-white border"><i class="fas fa-clone mr-3"></i>Учет фильтров</button>
</div>
<div class="card card-body pb-1 mt-3">
				    <?php
					    $to = DB('*','tech_work','status="done" and tech='.$idtech.' order by datetime DESC');
					    
						foreach ($to as $n) { 
							$date = date("d.m", strtotime($n['datetime']));
							
						?>
							
							<p><strong>ТО <?=$n['type']?></strong> для <strong><?=DBOnce('name','tech_tech','id='.$idtech)?></strong> с наработкой <strong><?=$n['motchas']?> м/ч</strong> выполнено <?=$date?></p>
							
							
					<?php	} ?>
				    
				    
				  </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="date" class="col-form-label"><i class="far fa-calendar-alt mr-2"></i> Дата начала</label>
                        <input type="date" class="form-control" name="datestart" id="datestart" value="<?= $startDate ?>" max="<?= $now ?>">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="date" class="col-form-label"><i class="fas fa-calendar-alt mr-2"></i> Дата окончания</label>
                        <input type="date" class="form-control" name="dateend" id="dateend" value="<?= $endDate ?>" max="<?= $now ?>">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="reportTech" class="col-form-label text-white w-100">Сформировать</label>
                        <button name="reportTech" id="reportTech" class="btn btn-primary">Сформировать отчет</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="card mt-3" id="reportBox">
    <?php include 'tech-tech-table.php'; ?>
</div>
<script>
    $('#datestart').on('change', function () {
        var maxValue = $('#datestart').attr('max');
        var dateStart = $('#datestart').val();
        if (dateStart > maxValue) {
            $('#datestart').val(maxValue);
            dateStart = maxValue;
        }
        if (dateStart > $('#dateend').val()) {
            $('#dateend').val(dateStart);
        }
    });
    $('#dateend').on('change', function () {
        var maxValue = $('#dateend').attr('max');
        var dateEnd = $('#dateend').val();
        if (dateEnd > maxValue) {
            $('#dateend').val(maxValue);
            dateEnd = maxValue;
        }
        if (dateEnd < $('#datestart').val()) {
            $('#datestart').val(dateEnd);
        }
    });

    $('#reportTech').on('click', function () {
        var startDate = $('#datestart').val();
        var endDate = $('#dateend').val();
        if (startDate && endDate) {
            $.post("/ajax.php", {
                techreport: '<?= $idtech ?>',
                startDate: startDate,
                endDate: endDate
            }, function (data) {
                if (data) {
                    $('#reportBox').html(data);
                }
            });
        }
    });
</script>
<?php endif; ?>
