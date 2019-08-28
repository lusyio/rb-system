<?php 
	include_once('SmsaeroApiV2.class.php');
    use SmsaeroApiV2\SmsaeroApiV2;
    $smsaero_api = new SmsaeroApiV2('mr-kelevras@yandex.ru', 'sPoFY1rcyAd6DbID44ZM4xstSUPX', 'Rubezh'); // api_key из личного кабинета
    if (isset($_POST["smenareport"])) {
	    $idsmena = $_POST['id'];
	    $sql = $pdo->prepare("UPDATE `brigada` SET `prostoy` = :prostoy, `repair` = :repair, hozwork = :hozwork, updatework = :updatework, comment = :comment, report = 'yes'  WHERE id=".$idsmena);
		$sql->execute(array('prostoy' => $_POST["prostoy"], 'repair' => $_POST["repair"], 'hozwork' => $_POST["hozwork"], 'updatework' => $_POST["updatework"], 'comment' => $_POST["comment"]));
		
		if ($sql) {
			$sql = 'SELECT id, datestart, daynight, marten, lpk, don, oplmat, brigadir, comment, plansmena FROM `brigada` WHERE id = "'.$idsmena.'"';
			$sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
			foreach ($sql as $result) {
						     
					    $plansmena = $result['plansmena']; 
					    $brigadir = $result['brigadir'];
					    $marten = $result['marten'];
					    $lpk = $result['lpk'];
					    $don = $result['don'];
					    $oplmat = $result['oplmat'];
						$reportsum = $marten + $lpk + $don + $oplmat;    
						$comment = $result['comment'];
						
						if ($reportsum > 0) {
							$reportsum2 = 'За смену взяли '.$reportsum.' т. ('.$marten.'/'.$lpk.'/'.$don.') ';
						} else {
							$reportsum2 = '';
						}
						if ($result['daynight'] == "day") {
						    $daynight = 'Дневная';
						}
						if ($result['daynight'] == "night") {
							$daynight = 'Ночная';
						}
						if ($result['daynight'] == "megaday") {
							$daynight = 'МегаДневная';
						}
						
						if ($reportsum > $plansmena) {
							$plansum = 'План в '.$plansmena.' т. выполнен. ';
						}
						
						if ($reportsum < $plansmena) {
							$plansum = 'План в '.$plansmena.' т. НЕ выполнен. ';
						}
						
						if ($marten > 0) {
							$martenprint = '<br>- Мар.: '.$marten.' т.';
						} else {
							$martenprint = '';
						}
						
						if ($lpk > 0) {
							$lpkprint = '<br>- ЛПК: '.$lpk.' т.';
						} else {
							$lpkprint = '';
						}
						
						if ($don > 0) {
							$donprint = '<br>- Дон.: '.$don.' т.';
						} else {
							$donprint = '';
						}
						
						$newDate = date("d.m", strtotime($result['datestart']));
			//	        $report = $plansum.'<br>За смену взяли '.$reportsum.' т.:'.$martenprint.$lpkprint.$donprint.'<br>'.$result['comment'];
			}
			echo '<p class="hidden">';	
		
		
		
			if ($plansmena > 0) {
			   var_dump($smsaero_api->send(['79265861133'],$plansum.' Взяли '.$reportsum.'. '.$newDate.' '.$daynight.' '.$brigadir.': '.$comment, 'DIRECT')); // Отправка сообщений
			}  
			   
			   
			   echo '</p>';
			   
			
			
			successmes($url);
		} else {
			errormes($url);
		}
	}
?>
<div class="info-block">
	<div class="inside">
<?php
$countreports = DBOnce('COUNT(*) as count','brigada','report="no" and datestart <= "'.$now.'"');
if ($countreports > 0) {
?>

		<h2>Отчет о смене</h2>
		<form method="post" id="smenareport"> 
		<div class="form-group">
	      <label for="date" class="col-form-label"><i class="fas fa-users mr-2"></i> Смена</label>
	        		<select name="id" class="form-control">
			<?php
				$sql = "SELECT id, datestart, daynight, brigadir, report FROM `brigada` WHERE report='no' AND datestart <= '".$now."' ORDER by datestart";
				
				$sql = $pdo->prepare($sql);
				$sql->execute();
				$sql = $sql->fetchAll(PDO::FETCH_BOTH);
				
				foreach ($sql as $result) {
					     if ($result['daynight'] == "day") {
						    	$daynight = 'Дневная';
						    } 
						if ($result['daynight'] == "night") {
						    	$daynight = 'Ночная';
						    }
						 if ($result['daynight'] == "megaday") {
						    	$daynight = 'МегаДневная';
						    }
						    
						$newDate = date("d.m", strtotime($result['datestart']));
				        echo '<option value="'.$result['id'].'">'.$newDate.' '.$daynight.' - '.$result['brigadir'].'</option>';
				    }	
				?>
		</select>
	    </div>
	    <div class="form-group">
	      <label for="staticEmail" class="col-form-label"><i class="fas fa-clock mr-2"></i> Время простоя (мин.)</label>
		  <input type="time" min="0:00" max="24:00" class="form-control" name="prostoy" value="00:00">
	    </div>
	    <div class="form-group d-none">
	      <label for="staticEmail" class="col-form-label"><i class="fas fa-tools mr-2"></i> Был ремонт?</label>
	        <select name="repair" class="form-control">
				<option value="no">Нет</option>
				<option value="yes">Да</option>
			</select>
	    </div>
	    <div class="form-group d-none">
	      <label for="staticEmail" class="col-form-label"><i class="fas fa-broom mr-2"></i> Были хоз.работы?</label>
	        <select name="hozwork" class="form-control">
				<option value="no">Нет</option>
				<option value="yes">Да</option>
			</select>
	    </div>
	    <div class="form-group d-none">
	      <label for="staticEmail" class="col-form-label"><i class="fas fa-hammer mr-2"></i> Было улучшение производства?</label>
	        <select name="updatework" class="form-control">
				<option value="no">Нет</option>
				<option value="yes">Да</option>
			</select>
	    </div>
	     <div class="form-group">
	      <label for="exampleTextarea"><i class="fas fa-clipboard mr-2"></i> Краткий отчет</label>
	      <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Н-р: Остановка Триумфа в 00:20-03:15. Ремонт БЕЛАЗа." required></textarea>
	    </div>
	    <hr>
	    <button type="submit" name="smenareport" class="btn btn-success">Добавить отчет <i class="fas fa-check ml-2"></i></button>
		</form>

	
<?php } else { echo '<p>Все отчеты добавлены!</p>'; }?>
</div>
</div>
