<?php 
 	ini_set('error_reporting', E_ALL);
 	ini_set('display_errors', 1);
 	ini_set('display_startup_errors', 1);	
	include 'bdcon.php'; 
	
	$counts = DBOnce2('count(*) as kolvo','weighing','');
	
	// Выставление оценок для смен
			$month = date("Y-m-01");
			$now = date("Y-m-d");
			$sql = "SELECT DATE_FORMAT(`prostoy`, '%Hч. %iмин.') AS `prostoy2`, datestart, timestart, brigadir, marten, lpk, don, daynight, comment, plansmena, prostoy, repair, hozwork, id, updatework FROM brigada WHERE datestart between '".$month."' and '".$now."' and report = 'yes' ORDER BY id DESC";
			$sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result) {	
				
				$sumsmena = $result['marten'] + $result['lpk'] + $result['don'];
					   
/*
					    if ($result['repair'] == 'yes') {  $repball = 0;  } 
					    if ($result['repair'] == 'no') {   $repball = 1;  } 
					    if ($result['hozwork'] == 'yes') {  $repball = 1; }
*/
					    if ($result['hozwork'] == 'no') {   }
					    if ($result['prostoy'] <= '01:00:00') {$prostoyball = 1;} else { $prostoyball = 0;  }
					    
					    $plansmena = $result['plansmena'];
					    
					    if ($sumsmena >= $plansmena) {
						    $planball = 1;
						    $updatemeta = 'UPDATE brigada SET plandone = "1" WHERE id = "'.$result['id'].'"';
						    $updatemeta = $pdo->prepare($updatemeta);
							$updatemeta->execute();
						    
						    
					    } else {
						    $planball = 0;
						    $updatemeta = 'UPDATE brigada SET plandone = "0" WHERE id = "'.$result['id'].'"';
						    $updatemeta = $pdo->prepare($updatemeta);
							$updatemeta->execute();
					    }
					    
					    if ($plansmena == 0) {
						    $updatemeta = 'UPDATE brigada SET plandone = "1" WHERE id = "'.$result['id'].'"';
						    $updatemeta = $pdo->prepare($updatemeta);
							$updatemeta->execute();
					    } 
					    $repball = 1;
					    $meta = $planball + $repball + $prostoyball + 2;
					    
					    if ( $sumsmena == 0 and $result['updatework'] == 'no') { $meta = $repball;}
					    if ( $result['updatework'] == 'yes' or $plansmena == 0 ) { $meta = 5; }
					     if ($result['updatework'] == '') {  }
					    
						$updatemeta = 'UPDATE brigada SET meta = "'.$meta.'" WHERE id = "'.$result['id'].'"';
						$updatemeta = $pdo->prepare($updatemeta);
						$updatemeta->execute();
					    
			}
	
	
	if(!empty($counts)) {
			
			
			
			// Обновление побочки смен
			$sql = "SELECT datestart, timestart, brigadir, dateend, timeend, id FROM brigada WHERE datestart between '".$month."' and '".$now."' ORDER BY id DESC";
		    $sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result) {	
			
			$datacreateresult = "TYP_EVENT='Покупка (приход от поставщика)' and DATETIME_CREATE between '".$result['datestart']." ".$result['timestart']."' and '".$result['dateend']." ".$result['timeend']."'";
			
			$martensmena = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №1 МАРТЕН' and ".$datacreateresult))/1000);
			$lpksmena = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №2 ЛПК' and ".$datacreateresult))/1000);
			$donsmena = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №3 ДОНЫШКИ' and ".$datacreateresult))/1000);
			$oplmat = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Оплаченный материал' and ".$datacreateresult))/1000);
			
			
			$updatesmena = 'UPDATE brigada SET marten = "'.$martensmena.'" WHERE id = "'.$result['id'].'"';
			$updatesmena = $pdo->prepare($updatesmena);
			$updatesmena->execute();
			
			$updatesmena = 'UPDATE brigada SET lpk = "'.$lpksmena.'" WHERE id = "'.$result['id'].'"';
			$updatesmena = $pdo->prepare($updatesmena);
			$updatesmena->execute();
			
			$updatesmena = 'UPDATE brigada SET don = "'.$donsmena.'" WHERE id = "'.$result['id'].'"';
			$updatesmena = $pdo->prepare($updatesmena);
			$updatesmena->execute();
			
			$updatesmena = 'UPDATE brigada SET oplmat = "'.$oplmat.'" WHERE id = "'.$result['id'].'"';
			$updatesmena = $pdo->prepare($updatesmena);
			$updatesmena->execute();
			
			
	//		echo $martensmena. ' - ' . $lpksmena . ' - ' . $donsmena . ' - ' .$oplmat . '<br>';
	}
	
	echo '<hr>';
	
	// мартен лпк и донышки за месяц
	
	$datemonth = "DATETIME_CREATE between '".$month." 00:00:00' and '".$now." 23:59:59'";

	$marten = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №1 МАРТЕН' and TYP_EVENT='Покупка (приход от поставщика)' and ".$datemonth))/1000);
	$lpk = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №2 ЛПК' and TYP_EVENT='Покупка (приход от поставщика)' and ".$datemonth))/1000);
	$don = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Карта выборки №3 ДОНЫШКИ' and TYP_EVENT='Покупка (приход от поставщика)' and ".$datemonth))/1000);
	$oplmat = okr((DBOnce2("SUM(NETTO)","weighing","GRUZ_NAME='Оплаченный материал' and TYP_EVENT='Покупка (приход от поставщика)' and ".$datemonth))/1000);
	
	$updatemonth = 'UPDATE zenno SET value = "'.$marten.'" WHERE id = "10"';
	$updatemonth = $pdo->prepare($updatemonth);
	$updatemonth->execute();

	$updatemonth = 'UPDATE zenno SET value = "'.$lpk.'" WHERE id = "11"';
	$updatemonth = $pdo->prepare($updatemonth);
	$updatemonth->execute();

	$updatemonth = 'UPDATE zenno SET value = "'.$don.'" WHERE id = "12"';
	$updatemonth = $pdo->prepare($updatemonth);
	$updatemonth->execute();

	$updatemonth = 'UPDATE zenno SET value = "'.$oplmat.'" WHERE id = "14"';
	$updatemonth = $pdo->prepare($updatemonth);
	$updatemonth->execute();
				 
				 
	$allpoboch = $marten + $lpk + $don + $oplmat;
	
	echo 'За месяц: '.$allpoboch . '<br>';
	//План смены
	} else { }
	
	
	
	$nowtime = date("G"); 
	$yesterday = Date("Y-m-d",strtotime("-1 day"));
	// Сколько взяли побочки за месяц
	$zennopobo = DBOnce('value','zenno','id=1'); 
	// План побочки на месяц
	$planpoboch = DBOnce('value','zenno','id=7');  
	$day = date('d');
	$daysm = date('t');
	$dayost2 = $daysm - $day;
	if ($dayost2 <= 0) {
		$dayost = 1;
	} else {
		$dayost = $daysm - $day;
	}
	if ($dayost2 == 1) {
		$dayost = 1;
	} 	
	if ($dayost2 > 1) {
		$dayost = $daysm - $day;
	}
	
	if ($nowtime >=7 and $nowtime <19) {
		$datasmen = $now;
		$daynight = 'day';
		$kof = '1.05';
		$kolsmen = 2; // без ночных ставь 1
		if ($kolsmen != 1) {
			$minus = 1;
		} else {
			$minus = 0;
		}
		$prosplan = (($planpoboch - $zennopobo)/($dayost*$kolsmen-$minus))*$kof;
		
		if ($prosplan < 0) {
			$prosplan = 0;
		}
		if ($prosplan > 1220) {
			$prosplan = 1207;
		}
		$prosplan2 = number_format($prosplan,0,'',' ');
		$pobochost = $planpoboch - $zennopobo;
		if ($pobochost == $prosplan or $pobochost < 1000) {
			
			$sql = "SELECT marten, lpk, don, oplmat FROM brigada WHERE datestart = '".$datasmen."' and daynight = '".$daynight."'";
			$sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result2) {
				$sumsmenanow = $result2['marten'] + $result2['lpk'] + $result2['don'] + $result2['oplmat'];
			}
			$prosplan = $prosplan + $sumsmenanow;
		} else {
			
		}
		if ($pobochost <= 0) {
			$prosplan = 0;
		}
	//	if ($nowtime >=7 and $nowtime <12) {
			 
		$plansmena = 'UPDATE brigada SET plansmena = "'.$prosplan.'" WHERE datestart = "'.$datasmen.'" and daynight = "'.$daynight.'"';
		$plansmena = $pdo->prepare($plansmena);
		$plansmena->execute();
			 
	//	}
	} else {
		
	}
	if ($nowtime >=19 and $nowtime <24) {
		$datasmen = $now;
		$daynight = 'night';
		$kof = '1.02';
		$pobochost = $planpoboch - $zennopobo;
		$prosplan = ($pobochost/($dayost*2-2))*$kof;
		if ($prosplan < 0) {
			$prosplan = 0;
		}
		if ($prosplan > 1190) {
			$prosplan = 1190;
		}
		$prosplan2 = number_format($prosplan,0,'',' ');
		if ($pobochost == $prosplan) {
			$sql = "SELECT marten, lpk, don, oplmat FROM brigada WHERE datestart = '".$datasmen."' and daynight = '".$daynight."'";
				    $sql = $pdo->prepare($sql);
			$sql->execute();
			$sql = $sql->fetchAll(PDO::FETCH_BOTH);
			
			foreach ($sql as $result2) {
			$sumsmenanow = $result2['marten'] + $result2['lpk'] + $result2['don'] + $result2['oplmat'];
		}
			$prosplan = $prosplan + $sumsmenanow;
		} else {
			
		}
		if ($pobochost <= 0) {
			$prosplan = 0;
		}
		$plansmena = 'UPDATE brigada SET plansmena = "'.$prosplan.'" WHERE datestart = "'.$datasmen.'" and daynight = "'.$daynight.'"';
		$plansmena = $pdo->prepare($plansmena);
		$plansmena->execute();
	} else {
		
	}

	if(!empty($counts)) {
	//Обновление значения побочки за месяц
	
	$pobochall = 'UPDATE zenno SET value = "'.$allpoboch.'" WHERE id = "1"';
	$pobochall = $pdo->prepare($pobochall);
	$pobochall->execute();	
	
	// сколько всего щебня за месяц отгрузили
	$vyborka = '(GRUZ_NAME = "Щебень 0-20 мм." OR GRUZ_NAME = "Щебень 20-40 мм." OR GRUZ_NAME = "Щебень 20-70 мм.") AND TYP_EVENT="Реализация (отгрузка покупателю)"';
	$allshebsum = okr((DBOnce2('SUM(NETTO)','weighing',$vyborka.' and '.$datemonth))/1000);
	
	echo 'Щебень - '.$allshebsum;
	$shebenreal = 'UPDATE zenno SET value = "'.$allshebsum.'" WHERE id = "13"';
	$shebenreal = $pdo->prepare($shebenreal);
	$shebenreal->execute();	
	
	echo '<hr>';
	
	$vagon = DBOnce2("count(*) as kolvo","weighing","VESYNAME='Весы 3' and TYP_EVENT='Реализация (отгрузка покупателю)' and ".$datemonth);
	echo 'В '.$vagon.'<br>';
	
	$vagonupdate = 'UPDATE zenno SET dop = "'.$vagon.'" WHERE id = "2"';
	$vagonupdate = $pdo->prepare($vagonupdate);
	$vagonupdate->execute();	
	
	$cars = DBOnce2("count(*)","weighing","VESYNAME='Весы 1' and TYP_EVENT='Реализация (отгрузка покупателю)' and ".$datemonth);
	
	echo 'М '.$cars;
	$carsupdate = 'UPDATE zenno SET dop = "'.$cars.'" WHERE id = "3"';
	$carsupdate = $pdo->prepare($carsupdate);
	$carsupdate->execute();	
	
	$deleteerror = "DELETE FROM `weighing` WHERE `TYP_EVENT` = 'ОШИБКА взвешивания' and ".$datemonth;
	$deleteerror = $pdoves->prepare($deleteerror);
	$deleteerror->execute();		 
	} 
	
	echo '<hr>';
	
	$tech = DB('*','tech_tech','');
	
	foreach ($tech as $n) {
		
		$tech = $n['id'];
		// проверям есть ли актуальное ТО
		$to = DBOnce('motchas','tech_work','status="inwork" and tech='.$n['id']);
		
		// смотрим последний моточасы для машины			
		$mch = DBOnce('motchas','tech_norm','tech='.$n['id'].' order by datetime DESC');
		
		$mch2 = DBOnce('motchas','tech_work','tech='.$n['id'].' and status="done" order by datetime DESC');
		
		$interval = 500;
		$otschet = 0;
		
		if ($tech == 41 or $tech == 42 ) {
			$interval = 500;
		}

        if ($tech == 62 ) {
            $interval = 250;
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
		
		$new = (okr(($mch - $otschet) / $interval)*$interval) - ($mch - $otschet);
		
		if (!empty($mch2)) {
			$razn = $mch - $mch2;
		} else {
			$razn = 1;
		}
		
		echo '<p>' . DBOnce('name','tech_tech','id='.$tech) . ': ' . $mch . ' м/ч: ' . $new . '. Разница = '.$razn.'</p>';
		
		
		if ($new <= 100 and $new > 0) {
			
			if(empty($to)) {
				echo 'Хочу добавить ТО ';
				if ($razn == 1 or $razn >= 400) {

				    if ($n['id']  != '55') {
                        $sql = $pdo->prepare("INSERT INTO `tech_work` SET `tech` = :tech, `status` = 'inwork', `motchas` = :motchas, `datetime` = :datetime");
                        $sql->execute(array('tech' => $n['id'], 'motchas' => $mch, 'datetime' => $datetime));
                        $to = DBOnce('tech', 'tech_work', 'status="inwork" and tech=' . $n['id']);
                        echo 'Добавил ТО для техники ' . DBOnce('name', 'tech_tech', 'id=' . $n['id']) . '<hr>';
                    }
				} else {
					echo 'Но не добавляю :-(';
				}
			}
			
			
			
		} 

	}
	
	
	// Обновляем время
	$arr = [
				  'января',
				  'февраля',
				  'марта',
				  'апреля',
				  'мая',
				  'июня',
				  'июля',
				  'августа',
				  'сентября',
				  'октября',
				  'ноября',
				  'декабря'
				];
//	date_default_timezone_set('Russia/Moscow');
	$day = date('d', time());
	$time = date('H:i:s', time());
	$month = (date('n', time()))-1;
	$dateupdate = $day.' '.$arr[$month].' в '.$time;
	if(!empty($counts)) {
	$zennoupdate = 'UPDATE zenno SET dop = "'.$dateupdate.'" WHERE id = "9"';
	$zennoupdate = $pdo->prepare($zennoupdate);
	$zennoupdate->execute();	
	} else {
		
	}
?>