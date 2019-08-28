<?php 
	
	include_once('SmsaeroApiV2.class.php');
    use SmsaeroApiV2\SmsaeroApiV2;
    $smsaero_api = new SmsaeroApiV2('mr-kelevras@yandex.ru', 'sPoFY1rcyAd6DbID44ZM4xstSUPX', 'Rubezh'); // api_key из личного кабинета
    
    
    if (isset($_POST["smenareport"])) {
	   $date = $_POST["datesmen"];
	   $newDate = date("d.m", strtotime($date));
	   $report = $_POST["report"];
	   	
	   $reportold = DBOnce("*","tech_report","datetime = '".$date."'");
	   
	   if (empty($reportold)) {
		   
		   
	   		$sql = $pdo->prepare("INSERT INTO `tech_report` SET `report` = :report, `datetime` = :datetime");
	   		$sql->execute(array('report' => $report, 'datetime' => $date));
	   		echo '<p class="hidden">';	
		
	   		var_dump($smsaero_api->send(['79265861133'],'Гараж '.$newDate.': '.$report, 'DIRECT')); // Отправка сообщений
			echo '</p>';
		   if (!empty($sql)) {
				successmes($url);
			} else {
				errormes($url);
			}
	   } else {
		   errormes($url);
	   }
	}
	$alltech = DB('*','tech_tech','id!=0');
	$tech = [];
	foreach ($alltech as $n) {
		$tech[] = $n['name'];
	}
?>
<div class="info-block">
	<div class="inside">

		<h2>Отчет о работе механиков</h2>
		<form method="post" id="smenareport"> 
		<div class="form-group">
	      <label for="date" class="col-form-label"><i class="far fa-calendar-alt mr-2"></i> Укажите дату</label>
	        <input type="date" class="form-control" name="datesmen" value="<?php echo $now; ?>">
	    </div>
	     <div class="form-group">
	      <label for="exampleTextarea"><i class="far fa-clipboard mr-2"></i> Краткий отчет о работе</label>
	      <br>
	      <?php foreach($tech as $n) { ?>
	      <button type="button" value="<?=$n?>" class="btn btn-link addtech pl-0"><ins><?=$n?></ins></button>
	      <?php } ?>
	      <textarea class="form-control" name="report" id="report" rows="3" placeholder="Н-р: Замена масла двигателя на CAT 325" required></textarea>
	    </div>
	    <hr>
	    <button type="submit" name="smenareport" class="btn btn-success">Добавить отчет <i class="fas fa-check ml-2"></i></button>
		</form>

	
</div>
</div>
<script>
$(document).ready(function(){
	$( ".addtech" ).click(function() {
	    var txt = $.trim($(this).text()) + ' ';
	    var box = $("#report");
	    box.val(box.val() + txt);
	    $("#report").focus();
	});
});
</script>
<style>
	.addtech {width: auto !important};
</style>
<?php
	
if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
 

$delid = $_POST['del_id'];

$deletesql = $pdo->prepare('DELETE from tech_report WHERE `id` = :id');
$deletesql->execute(array('id' => $delid));	

	if ($deletesql) {
	successmes($url);
	    } else {
	errormes($url);
	}	

} 
		$sql = 'SELECT * FROM tech_report WHERE datetime '.$bwmonth.' order by datetime DESC';
		$sql = $pdo->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		
		if (!empty($sql)) {
		?>
<div class="info-block">
	<div class="inside">
		<h6 class="mb-3">Отчеты за месяц</h6>
		<table class="table table-hover table-striped w-100">
		  	<thead>
			    <tr>
			      <th scope="col">Отчет</th>
			      <th scope="col">Дата</th>
			      <th scope="col"><i class="fas fa-trash-alt"></i></th>
			    </tr>
		  	</thead>
		  	<tbody>
		 <?php
		foreach ($sql as $result) {		
			$newDate = date("d.m", strtotime($result['datetime']));
			echo '<tr><td><p>'.$result['report'].'</p></td><td>'.$newDate.'</td><td><form method="post"><input type="text" class="form-control hidden" name="del_id" value="'.$result['id'].'"><button type="submit" class="btn btn-link p-0"><i class="fas fa-trash-alt text-danger"></i></button></form></td></tr>';
		} ?>
		</tbody>
		</table>
	</div>
</div>
		<?php
		}
	?>