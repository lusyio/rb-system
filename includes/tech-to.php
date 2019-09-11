<?php
// вывод техники
$tech = DB('*','tech_tech','');
$zaplist = DB('*','tech_oil','tech="41"');	
$tolist = DB('*','tech_to','id != 0 group by tech');
function arrayzap($array) {
	$results = [];
	$data    = explode("/", $array);
	foreach ($data as $row) {
	    $line = explode(":", $row);
	    $results[] = [$line[0], $line[1]];
	}
	return $results;
}
if (isset($_POST['delto'])) { //проверяем, есть ли переменная
	
	$url = '/tech-to/';
	$iddel = $_POST['delto'];
	$deletesql = $pdo->prepare('DELETE from `tech_to` WHERE `id` = :id');
	$deletesql->execute(array('id' => $iddel));
    if ($deletesql) {
		successmes($url);
	} else {
		errormes($url);
	}
	
}
?>
<style>
	.form-control {
		 height: 34px;
	}
</style>

<div id="toCard" class="card">
	<div class="card-body">
		
<!-- 		<form method="post"> -->
			
			<div class="row mb-3">
				
				<div class="col-sm-3 font-weight-bold"><i class="fas fa-truck-monster mr-3"></i>Техника</div>
				<div class="col-sm-9">
					
					<select name="tech" id="tech" class="form-control mb-1">
						<option selected disabled>Выберите технику</option>
						<?php foreach ($tech as $n) { ?>
						<option value="<?=$n['id']?>"><?=$n['name']?></option>
						<?php } ?>
						
					</select>
					<small class="text-secondary">Выберите технику, для которой делаем ТО</small>
				</div>
			</div>
			
			<div class="row mb-3">
				
				<div class="col-sm-3 font-weight-bold"><i class="fas fa-clone mr-3"></i>Тип ТО</div>
				<div class="col-sm-9">
					
					<select name="to" id="to" class="form-control mb-1">
						
						<option value="500">ТО 500</option>
						<option value="1000">ТО 1000</option>
						<option value="2000">ТО 2000</option>
						<option value="3000">ТО 3000</option>
					</select>
					<small class="text-secondary">Если у техники всегда одно и тоже ТО, то просто добавляем ТО 500</small>
				</div>
			</div>
			
			<hr>
			
			<div class="row mb-3">
				
				<div class="col-sm-3 font-weight-bold"><i class="fas fa-wrench mr-3"></i>Запчасти</div>
				<div class="col-sm-9 zapBlock">
					
					<div class="row mb-3 zapBlockIn">
						<div class="col-sm-8">
							<select name="zaplist" class="form-control mb-1 zaplist">
								
								<option selected disabled>Выберите технику</option>
								
							</select>
							<small class="text-secondary">Запчасть</small>
						</div>
						
				
						<div class="col-sm-4">
							
							<input class="form-control countzap mb-1" type="number" value="0" placeholder="Н-р: 5"/>
							<small class="text-secondary">Кол-во (шт. или л.)</small>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="text-center w-100"><a id="addZap" class="btn btn-link font-weight-bold rounded">+</a></div>
			
			<hr>
			
			<button id="makeTO" class="btn btn-primary w-100">Добавить ТО для техники</button>
			
<!-- 		</form> 7:15/2:30/5:12 -->
	</div>
</div>
<div id="toEditCard" class="card mt-4 d-none">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-3 font-weight-bold"><i class="fas fa-truck-monster mr-3"></i>Техника</div>
            <div class="col-sm-9">
                <p id="editTechName"></p>
                <input id="editTechId" type="hidden" value="">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-3 font-weight-bold"><i class="fas fa-clone mr-3"></i>Тип ТО</div>
            <div class="col-sm-9">
                <p id="editTOType"></p>
                <input id="editTOId" type="hidden" value="">

            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-sm-3 font-weight-bold"><i class="fas fa-wrench mr-3"></i>Запчасти</div>
            <div id ="editZapBox" class="col-sm-9">
            </div>
        </div>
        <div class="text-center w-100"><a id="addEditZap" class="btn btn-link font-weight-bold rounded">+</a></div>
        <hr>
        <div class="row">
            <div class="col-12 col-md-6">
                <button id="updateTO" class="btn btn-success w-100" disabled>Сохранить изменения ТО</button>
            </div>
            <div class="col-12 col-md-6">
                <button id="clearTO" class="btn btn-danger w-100">Сбросить изменения ТО</button>
            </div>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3" style="font-weight: 800">Добавленные ТО</h5>
<?php foreach ($tolist as $n) : 
	$idtech = $n['tech'];
	$filename = 'images/'.$idtech.'.jpg';
	if (file_exists($filename)) {
		$imgname = $idtech;
	} else {
		$imgname = 'no-image';
	} 
	$nameTech = DBOnce('name','tech_tech','id='.$idtech);
?>
	<div class="card mb-3">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-1">
					<img src="/images/<?=$imgname?>.jpg" >
				</div>
				<div class="col-sm-11">
					<h6 class="mt-0 mb-3" style="font-weight: 800"><?=$nameTech?></h6>
					<?php $tofortech = DB('*','tech_to','tech='.$idtech);
						foreach ($tofortech as $p) : ?>
						
						<button class="btn btn-light font-weight-bold w-100 mb-3 text-left" type="button" data-toggle="collapse" data-target="#collapseTechTo<?=$p['id']?>" aria-expanded="false" aria-controls="collapseTechTo<?=$p['id']?>">
						    ТО <?=$p['type']?>
						  </button>
						  <div class="collapse" id="collapseTechTo<?=$p['id']?>">
						  <div class="card card-body mb-3 to-box">
                              <div class="to-zap-box">
						    <?php
							    $arrayzap = arrayzap($p['zap']);
									if (!empty($arrayzap)) {
									foreach ($arrayzap as $tr => $i)
										{
							//	  echo  "<br>$n:<br>";
										$c = 0;
									 	foreach ($i as $pr => $pp)
									 		{
										 		if ($c==0) {
											 		$namezap =  DBOnce('name','tech_oil','id='.$pp);
											 		$typezap =  DBOnce('type','tech_oil','id='.$pp);
											 		echo '<p>'.$namezap.' ';
										 		}  else {
											 		if ($typezap == 'Масло') {
												 		$edin = ' л';
											 		} else {
												 		$edin = ' шт';
											 		}
											 		echo '- '.$pp.$edin.'. </p>';
										 		}
									 	 		
										 		$c++;
								    		}
								  		} 
								  }
								  ?>
                              </div>
                              <div class="to-edit-box">

                              </div>
								  <hr>
                              <div class="row">
                                  <div class="col-sm-auto col-12">
								  <form method="post">
									  <input name="delto" value="<?=$p['id']?>" hidden/>
									  <button type="submit" class="btn btn-danger w-100"><i class="fas fa-trash-alt mr-3"></i>Удалить ТО</button>
								  </form>
                                  </div>
                                  <div class="col-sm-auto col-12">
                                  <button class="btn btn-warning w-100 go-edit-to" data-to-id="<?= $p['id'] ?>" data-tech-id="<?= $idtech ?>" data-tech-name="<?= $nameTech ?>" data-to-type="ТО <?=$p['type']?>"><i class="fas fa-pen-alt mr-3 go-edit-to"></i>Редактировать ТО</button>
                                  </div>
                              </div>

                          </div>
						</div>
						
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>		
<script>
$(document).ready(function () {
	$('#tech').bind('keyup change click', function () {
		value = $('#tech :selected').val();
		addSelect(value);
	});
	
	function addSelect(id) {
		id = $('#tech').val();
    	$.post("/ajax.php", {technorm: id, info: 'tech-ajax-select'},controlUpdate);
    	function controlUpdate(data) {
			$(".zaplist").empty().html(data);
		}
	}
	
	$('#addZap').on('click', function () {
        id = $('#tech').val();
    	$.post("/ajax.php", {technorm: id, info: 'tech-ajax-select2'},controlUpdate);
    	function controlUpdate(data) {
			$(".zapBlock").append(data);
		}
    });
	$('#addEditZap').on('click', function () {
        id = $('#editTechId').val();
    	$.post("/ajax.php", {technorm: id, info: 'tech-ajax-select-edit'},controlUpdate);
    	function controlUpdate(data) {
			$("#editZapBox").append(data);
		}
    });
    
    $('#makeTO').on('click', function () {
	    id = $('#tech').val();
	    to = $('#to').val();
	    console.log('start');
	    var zap = '';
        $('.zapBlockIn').each(function(){
		  var zapChast = $('.zaplist', this).val();
		  var zapCount = $('.countzap', this).val();
		  zap += zapChast + ':' + zapCount + '/';
		});
		zap = zap.slice(0,-1);
		console.log(zap);
		if (zap != '') {
		
		$.post("/ajax.php", {technorm: id, to: to, zaplist:zap, info: 'tech-add-to'},controlUpdate);
    	function controlUpdate(data) {
			
			swal({
			title: 'Успешно',
			text: '',
			type: 'success',
			allowOutsideClick: false,
				}).then(function (result) {
					if (result.value) {
					window.location = '/tech-to/';
		  		}
			})
		}
		}
    });

    $('.go-edit-to').on('click', function (e) {
        e.preventDefault();
        $('#clearTO').trigger('click');
        var toId = $(this).data('to-id');
        var techId = $(this).data('tech-id');
        var techName = $(this).data('tech-name');
        var toType = $(this).data('to-type');
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#toEditCard").offset().top
        }, 500);
        $.post("/ajax.php", {technorm: techId, info: 'tech-ajax-select-edit', toId: toId}, function (data) {
            $('#editTechId').val(techId);
            $('#editTOId').val(toId);
            $('#editTechName').text(techName);
            $('#editTOType').text(toType);
            $('#toEditCard').removeClass('d-none');
            $('#toCard').addClass('d-none');
            $('#editZapBox').append(data);
            $('#updateTO').prop("disabled", false);
        });
    });
    
    $('#clearTO').on('click', function () {
        $('#editZapBox').html('');
        $('#editTechId').val('');
        $('#editTOId').val('');
        $('#editTechName').text('');
        $('#editTOType').text('');
        $('#toEditCard').addClass('d-none');
        $('#toCard').removeClass('d-none');
        $('#updateTO').prop("disabled", true);
    });

    $('#toEditCard, #toCard').on('click', '.remove-row', function () {
        $(this).closest('.zap-row').remove();
    });

    $('#updateTO').on('click', function () {
        var techId = $('#editTechId').val();
        var toId = $('#editTOId').val();
        console.log('start');
        var zap = '';
        $('.zapBlockInEdit').each(function(){
            var zapChast = $('.zaplist', this).val();
            var zapCount = $('.countzap', this).val();
            zap += zapChast + ':' + zapCount + '/';
        });
        zap = zap.slice(0,-1);
        console.log(zap);
        if (zap !== '') {
            $.post("/ajax.php", {technorm: techId, toId: toId, zaplist:zap, info: 'tech-update-to'},controlUpdate);
            function controlUpdate(data) {

                swal({
                    title: 'Успешно',
                    text: '',
                    type: 'success',
                    allowOutsideClick: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location = '/tech-to/';
                    }
                })
            }
        }
    });
});
</script>