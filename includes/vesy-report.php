<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<div class="info-block">
	<div class="inside">
	<h3>Отчет с весовой</h3>
	<div class="row">
		<div class="col-sm-6">
			<label for="date" class="col-form-label"><i class="far fa-calendar-alt mr-2"></i> Дата и время начала периода</label>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
				      
				        <input type="date" class="form-control" id="datestart" value="<?php echo $yesterday;?>">
				    </div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
				        <input type="time" class="form-control" id="timestart" value="07:00">
				    </div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<label for="date" class="col-form-label"><i class="fas fa-calendar-alt mr-2"></i> Дата и время окончания периода</label>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
				        <input type="date" class="form-control" id="dateend" value="<?php echo $now;?>">
				    </div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
				        <input type="time" class="form-control" id="timeend" value="19:00">
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
	    <div class="form-group">
	      <label for="gruz" class="col-form-label"><i class="fas fa-box mr-2"></i> Груз</label>
			<select class="form-control" id="gruz">
				<option value="">Не выбрано</option>
				<?php
	$date = new DateTime();
	$date->modify('-2 month');
	$newd = $date->format('Y-m-d');
	$sql = "SELECT GRUZ_NAME FROM weighing WHERE TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '".$newd." 00:00:00' and '".$now." 23:59:59' and GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм' and GRUZ_NAME != '' GROUP BY GRUZ_NAME";
	$sql = $pdoves->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
	foreach ($sql as $result) {
		if ($result['GRUZ_NAME'] == 'СКРАП 15-50' and $iduser == 20) {
				
		}  else {
			echo '<option value="'.$result['GRUZ_NAME'].'">'.$result['GRUZ_NAME'].'</option>';
		} 
			  
	}

?>
				<option value="Карта выборки №1 МАРТЕН">Карта выборки №1 МАРТЕН</option>
				<option value="Карта выборки №2 ЛПК">Карта выборки №2 ЛПК</option>
				<option value="Карта выборки №3 ДОНЫШКИ">Карта выборки №3 ДОНЫШКИ</option>
				<option value="Оплаченный материал">Оплаченный материал</option>
			</select>			
	    </div>
		</div>
		<div class="col-sm-6">
	    <div class="form-group">
	      <label for="polychatel" class="col-form-label"><i class="far fa-user mr-2"></i> Получатель</label>
	        <select id="polychatel" class="form-control">
		        <option value="">Не выбрано</option>
		        
		        <?php
	$date = new DateTime();
	$date->modify('-5 month');
	$newd = $date->format('Y-m-d');
	$sql = "SELECT FIRMA_POL FROM weighing WHERE TYP_EVENT='Реализация (отгрузка покупателю)' and DATETIME_CREATE between '".$newd." 00:00:00' and '".$now." 23:59:59' and GRUZ_NAME != 'ЭКОСКРАП' and GRUZ_NAME != 'ЭКОСКРАП 400+' and GRUZ_NAME != 'ЭКОСКРАП 15-50 мм' and GRUZ_NAME != 'ЭКОСКРАП 200+' and GRUZ_NAME != 'ЭКОСКРАП 500-800 мм' and FIRMA_POL != '' GROUP BY FIRMA_POL";
	$sql = $pdoves->prepare($sql);
	$sql->execute();
	$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
	foreach ($sql as $result) {
		if ($result['FIRMA_POL'] == 'ООО МеталлСервис' and $iduser == 20) {
				
		}  else {
			echo '<option value="'.$result['FIRMA_POL'].'">'.$result['FIRMA_POL'].'</option>';
		}
	}

?>
			</select>
	    </div>
		</div>
	</div>
	    <button type="submit" id="reportdo" class="btn btn-primary mt-2">Сформировать отчет</button>

	</div>
</div>

<div class="position-relative">
	<div id="Loading" class="text-center" style=" position: absolute; top: 50px; width: 100%; ">Загрузка...</div>
</div>
<div id="reportview"></div>
<script>
$(document).ready(function() {
	$("#Loading").fadeOut('fast'); //hide when data's ready
    $( "#reportdo" ).click(function() {
	    $("#reportview").empty();
	    $("#Loading").fadeIn(); //show when submitting
	    var datestart = $('#datestart').val();
	    var timestart = $('#timestart').val();
	    var dateend = $('#dateend').val();
	    var timeend = $('#timeend').val();
	    var gruz = $('#gruz option:selected').val();
	    var polychatel = $('#polychatel option:selected').val();
        var report = 'vesy-report';
        var iduser = <?=$iduser?>;
        $.post("/ajax.php", {idreport: report, datestart: datestart, timestart: timestart, dateend: dateend, timeend: timeend, gruz: gruz, polychatel: polychatel, iduser: iduser },controlUpdate);
		function controlUpdate(data) {
			$("#Loading").fadeOut('fast'); //hide when data's ready
			$('#reportview').html(data);
		}
		return false;
    });
    
     

});
</script>
