<div class="info-block">
	<div class="inside">

	<h4>Общий отчет</h4>
	<div class="row">
		<div class="col-sm-6">
		<div class="form-group">
	      <label for="date" class="col-form-label"><i class="far fa-calendar-alt mr-2"></i> Дата начала</label>
	        <input type="date" class="form-control" name="datestart" id="datestart" value="<?php $yestt = Date("Y-m-d",strtotime("-1 day")); echo $yestt;?>">
	    </div>
		</div>
		<div class="col-sm-6">
	    <div class="form-group">
	      <label for="date" class="col-form-label"><i class="fas fa-calendar-alt mr-2"></i> Дата окончания</label>
	        <input type="date" class="form-control" name="dateend" id="dateend" value="<?php echo $now;?>">
	    </div>
		</div>
	</div>
	<select class="form-control" id="idr">
				<?php
					// админ
					if($iduser == "1") {
						echo '
						<option value="smen">Смены</option>
						<option value="kassad">Касса (Евдокимов)</option>
						<option value="kassa2">Касса 2 (Евдокимов)</option>
						<option value="kassav">Касса (Влада)</option>
						<option value="real">Отгрузки</option>
						<option value="gsm">ГСМ</option>
						<option value="spravka">Справка о взвешивании вагонов</option>
						<option value="eko">Эко</option>
						';

					} else {
					}
				?>
				<?php
					// Ласковский С.В.
					if($iduser == "2") {
						echo '
						<option value="smen">Смены</option>
						<option value="mech">Механики</option>
						<option value="kassad">Касса (Евдокимов)</option>
						<option value="kassa2">Касса 2 (Евдокимов)</option>
						<option value="kassav">Касса (Влада)</option>
						<option value="real">Отгрузки</option>
						<option value="gsm">ГСМ</option>
						<option value="eko">Эко</option>
						';

					} else {
					}
				?>
				<?php
					// Евдокимов
					if($iduser == "4") {
						echo '
						<option value="gsm">ГСМ</option>
						<option value="eko">Эко</option>
						<option value="kassad">Касса (Евдокимов)</option>
						<option value="kassa2">Касса 2 (Евдокимов)</option>
						<option value="kassav">Касса (Влада)</option>
						';

					} else {
					}
				?>
				<?php
					// Корнеев
					if($iduser == 8 or $iduser == 9 or $iduser == 10 or $iduser == 21 or $iduser == 23 or $iduser == 24 or $iduser == 27) {
						echo '
						<option value="smen">Смены</option>
						';

					} else {
					}
				?>
				<?php
					// Саида
					if($iduser == "18" or $iduser == 26) {
						echo '
						<option value="real">Отгрузки</option>
						';

					} else {
					}
				?>
				<?php
					// Влада
					if($iduser == "13") {
						echo '
						<option value="kassav">Касса (Влада)</option>
						<option value="real">Отгрузки</option>
						<option value="spravka">Справка о взвешивании вагонов</option>
						<option value="smen">Смены</option>
						';

					} else {
					}
				?>
				<?php
					// Егор
					if($iduser == "28") {
						echo '
						<option value="gsm">ГСМ</option>
						';

					} else {
					}
				?>
				<?php
					// Филиппович
					if($iduser == "20") {
						echo '
						<option value="real">Отгрузки</option>
						<option value="smen">Смены</option>
						<option value="gsm">ГСМ</option>
						<option value="kassad">Касса (Евдокимов)</option>
						<option value="kassav">Касса (Влада)</option>
						';

					} else {
					}
				?>
				<?php
					// Седышев
					if($iduser == "25") {
						echo '
						<option value="mech">Механики</option>
						<option value="gsm">ГСМ</option>
						';

					} else {
					}
				?>
	</select>
	    <button type="submit" name="reportdo" id="reportdo" class="btn btn-primary mt-3">Сформировать отчет</button>

	</div>
</div>
<div class="position-relative">
	<div id="Loading" class="text-center" style=" position: absolute; top: 50px; width: 100%; "><img src="/images/loader.gif" class="mb-3"/><p>Загрузка</p></div>
</div>
<div id="reportview"></div>
<script>
$(document).ready(function() {
	$("#Loading").fadeOut('fast'); //hide when data's ready
    $( "#reportdo" ).click(function() {
	    $("#reportview").empty();
	    $("#Loading").fadeIn(); //show when submitting
	    var datestart = $('#datestart').val();
	    var dateend = $('#dateend').val();
        var report = $('#idr option:selected').val();
        var iduser = <?=$iduser?>;
        $.post("/ajax.php", {idreport: report, datestart: datestart, dateend: dateend, iduser: iduser },controlUpdate);
		function controlUpdate(data) {
			$("#Loading").fadeOut('fast'); //hide when data's ready
			$('#reportview').html(data);
		}
		return false;
    });
    $('body').on('click', '.gsm-filter', function () {
        $('.gsm-filter').removeClass('btn-primary');
        $('.gsm-filter').addClass('btn-outline-primary');
        if ($(this).hasClass('active-filter')) {
            $(this).removeClass('active-filter');
            $('.report-entity').show()
        } else {
            var techName = $(this).data('filter-tech');
            $('.gsm-filter').removeClass('active-filter');
            $(this).removeClass('btn-outline-primary');
            $(this).addClass('btn-primary');
            $(this).addClass('active-filter');
            $('.report-entity').each(function (i, el) {
                console.log(el);
                if ($(el).data('tech') == techName) {
                    $(el).show();
                } else {
                    $(el).hide();
                }
            })
        }
    })
});
</script>
