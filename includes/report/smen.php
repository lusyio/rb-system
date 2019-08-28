<div class="zag"><h4 class="float-left">Отчет по сменам</h4>
<form method="post" action="/print.php" target="_blank" class="float-right d-none">
				    <input type="date" class="form-control hidden" name="datestart" value="<?=$datestart?>">
				    <input type="date" class="form-control hidden" name="dateend" value="<?=$dateend?>">
				    <input type="text" name="idreport" value="2" class="hid">
			    <button type="submit" class="btn btn-primary hidden-sm hidden-xs">Распечатать <i class="fas fa-print"></i></button>
			    </form><div class="clear"></div></div>
<?php
	
$sql = "SELECT avg(meta) AS meta_sum, SEC_TO_TIME( SUM( TIME_TO_SEC( `prostoy` ) ) ) AS timeSum, COUNT(report) as countsum FROM brigada WHERE datestart between '".$datestart."' and '".$dateend."' and report = 'yes'";
			    $marten = DBOnce('sum(marten)','brigada','datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $lpk = DBOnce('sum(lpk)','brigada','datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $don = DBOnce('sum(don)','brigada','datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $opl = DBOnce('sum(oplmat)','brigada','datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    
			    $all = $marten + $lpk + $don + $opl;
				$sql = $pdo->prepare($sql);
				$sql->execute();
				$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {
			    
			    $metaball = round($result['meta_sum'], 1);
			    
			    $timesum = $result['timeSum'];
			    $prost = date('Hч. iмин.', strtotime($timesum)); ?>
			  
			  <div class="d-flex">
				  <p class="mr-5">Средний балл: <strong><?=$metaball?></strong></p>
				  <p class="mr-5">Взяли побочки: <strong><?=$all?> т.</strong></p>
				  <p class="mr-5">Кол-во смен: <strong><?=$result['countsum']?></strong></p>
				  <p>Всего простой: <strong><?=$timesum?></strong></p>
			  </div>
			  
			    
			    
			    <?php
		    }
			echo '<div class="row mb-3">';
		    $sql2 = "SELECT brigadir, datestart, avg(meta) AS meta_sum, SEC_TO_TIME( SUM( TIME_TO_SEC( `prostoy` ) ) ) AS timeSum, COUNT(report) as countsum, SUM(plandone) AS plandonesum FROM brigada WHERE datestart between '".$datestart."' and '".$dateend."' and report = 'yes' GROUP BY brigadir ";
				$sql2 = $pdo->prepare($sql2);
				$sql2->execute();
				$sql2 = $sql2->fetchAll(PDO::FETCH_BOTH);
				foreach ($sql2 as $result) {
			    $metaball = round($result['meta_sum'], 1);
			    
			    $marten = DBOnce('sum(marten)','brigada','brigadir="'.$result['brigadir'].'" and datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $lpk = DBOnce('sum(lpk)','brigada','brigadir="'.$result['brigadir'].'" and datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $don = DBOnce('sum(don)','brigada','brigadir="'.$result['brigadir'].'" and datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    $opl = DBOnce('sum(oplmat)','brigada','brigadir="'.$result['brigadir'].'" and datestart between "'.$datestart.'" and "'.$dateend.'" and report = "yes"');
			    
			    $all = $marten + $lpk + $don + $opl;
			    $timesum = $result['timeSum'];
			    $prost = date('Hч. iмин.', strtotime($timesum));
			    echo '<div class="col-sm-4"><div class="info-block"><div class="inside"><h2>'.$result['brigadir'].'</h2>
			    <p>Средний балл: <strong>'.$metaball.'</strong></p>
			    <p>Кол-во тонн: <strong>'.$all.' т.</strong></p>
			    <hr><p>Кол-во смен: '.$result['countsum'].'</p><p>Всего простой: '.$prost.'</p><p>Выполнен план: '.$result['plandonesum'].' раз(а)</p></div></div></div>';
		    }
			echo '</div>';
			
			$sql = "SELECT DATE_FORMAT(`prostoy`, '%Hч. %iмин.') AS `prostoy2`, datestart, timestart, brigadir, marten, lpk, don, oplmat, daynight, comment, plansmena, prostoy, repair, hozwork, id, updatework, meta FROM brigada WHERE datestart between '".$datestart."' and '".$dateend."' and report = 'yes' ORDER BY id DESC";
			$sql = $pdo->prepare($sql);
						$sql->execute();
						$sql = $sql->fetchAll(PDO::FETCH_BOTH);
						
						foreach ($sql as $result) {	
				
				$sumsmena = $result['marten'] + $result['lpk'] + $result['don'] + $result['oplmat'];
					    $newDate = date("d.m", strtotime($result['datestart']));
					    
					    if ($result['daynight'] == 'day') {
						    $dainight = '<i class="fas fa-sun text-warning"></i>';
					    } else {
						    $dainight = '<i class="fas fa-moon text-primary"></i>';
					    }
					    
					    
						$marten = $result['marten'];
						
						if(!empty($marten)) {
							
						} else {
							$marten = '<span class="text-muted">0</span>';
						}
					    
					    $lpk = $result['lpk'];
						
						if(!empty($lpk)) {
							
						} else {
							$lpk = '<span class="text-muted">0</span>';
						}
						
						$don = $result['don'];
						
						if(!empty($don)) {
							
						} else {
							$don = '<span class="text-muted">0</span>';
						}
						
						$oplmat = $result['oplmat'];
						
						if(!empty($oplmat)) {
							
						} else {
							$oplmat = '<span class="text-muted">0</span>';
						}
						
					    if ($result['repair'] == 'yes') { $repair = '<span class="badge badge-warning">Ремонт</span>';   } 
					    if ($result['repair'] == 'no') {  $repair = '';   } 
					    if ($result['hozwork'] == 'yes') { $hozwork = '<span class="badge badge-info">Хоз.работы</span>';  }
					    if ($result['hozwork'] == 'no') {   $hozwork = '';   }
					    
					    
					    $plansmena = $result['plansmena'];
					    
					    if ($sumsmena >= $plansmena) {
						    $plansmena = '<span class="text-success">'.$plansmena.'</span>';
					    } else {
						    $plansmena = '<span class="text-danger">'.$plansmena.'</span>';
					    }
					    
					    
					    if ( $result['updatework'] == 'yes' ) { $updatework = '<span class="badge badge-primary">Улучшение производства</span>';}
					     if ($result['updatework'] == 'no') {   $updatework = '';   }
					    
					    
					    $meta = $result['meta'];
					  	
					    if ($meta >= 4) {
						    $badgeclass = 'badge-success';
					    }
					    
					    if ($meta == 3) {
						    $badgeclass = 'badge-warning';
					    }
					    
					    if ($meta < 3) {
						    $badgeclass = 'badge-danger';
					    }
					    
					    echo '<div class="reportsmena mb-5 mt-3"><hr><h5>'.$newDate.' '.$dainight.' '.$result['brigadir'].'<span class="meta '.$badgeclass.'">'.$meta.'</span></h5><hr><p><strong>'.$sumsmena.' т.</strong> (<span class="text-danger">'.$marten.'</span> / <span class="text-warning">'.$lpk.'</span> / <span class="text-success">'.$don.'</span> / <span class="text-primary">'.$oplmat.'</span>)</p><p>План: '.$plansmena.' т. Простой: '.$result['prostoy2'].'</p><p>'.$result['comment'].'</p><p>'.$repair.' '.$hozwork.' '.$updatework.'</p></div>';
				    }
			// запись в лог
		$period1 = date("d.m", strtotime($datestart));
		$period2 = date("d.m", strtotime($dateend));
		$log = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `user` = :user, `datetime` = :datetime");
		$action = 'Сформировал отчет по сменам. Период: '.$period1.' - '.$period2;
		$log->execute(array('action' => $action, 'user' => $iduser, 'datetime' => $datetime));
?>