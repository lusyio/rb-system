<?php
header('Content-type: text/html; charset=utf-8');
$Banks = simplexml_load_file('work/OUT/Banks.XML'); 

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);	
include 'bdcon.php'; 

/* Для каждого узла <character>, мы отдельно выведем имя <name>. */
foreach ($Banks->Bank as $bank) {
	$money = $bank->Summa;
	$date = $bank->Date;
	$date = date("Y-m-d H:i:s", strtotime($date));
	$what = $bank->EntExp;
	$type = $bank->Type;
	$kontragent = $bank->Kontragent;
	$money = mb_substr($money, 0, -3);
	
	$banks = DBOnce("count(*) as kolvo","bank","date='".$date."' and summa='".$money."'");
	
	
	if ($money > 0 and $banks == 0) {
		$sql = "INSERT INTO bank (date, what, type, kontragent, summa) VALUES ('".$date."','".$what."','".$type."','".$kontragent."','".$money."')";
		$sql = $pdo->prepare($sql);
		$sql->execute();
	}
}
?>
<?php 
 	
// остаток денежных средств
	 
		header('Content-type: text/html; charset=utf-8');
		$xml = simplexml_load_file('work/OUT/OstatkiDenejnihSredstv.XML'); 
		//print_r($xml);
		$ostatok = $xml->Ostatok; 
		$dateostatok = $xml->DateTime; 
		if ($ostatok != 0) {
		
			
		$ostatokupdate = 'UPDATE zenno SET value = "'.$ostatok.'" WHERE id = "30"';
		$ostatokupdate = $pdo->prepare($ostatokupdate);
		$ostatokupdate->execute();
		
		
		$dateostatokupdate = 'UPDATE zenno SET dop = "'.$dateostatok.'" WHERE id = "30"';
		$dateostatokupdate = $pdo->prepare($dateostatokupdate);
		$dateostatokupdate->execute();
		echo 'Ост '.$ostatok.' от '.$dateostatok.'<br>';
		}
		
		$kontr = DBOnce('SUM(summa)','bank','type="Оплата от покупателя" and date '.$bwmonth);
		echo $kontr;
		if (!empty($kontr)) {
			$totalcostsupdate = 'UPDATE zenno SET value = "'.$kontr.'" WHERE id = "2"';
			$totalcostsupdate = $pdo->prepare($totalcostsupdate);
			$totalcostsupdate->execute();
		}
		
		$kontr = DBOnce('SUM(summa)','bank','(type != "Оплата от покупателя" and type != "Перевод с другого счета") and date '.$bwmonth);
		if (!empty($kontr)) {
			$totalcostsupdate = 'UPDATE zenno SET value = "'.$kontr.'" WHERE id = "3"';
			$totalcostsupdate = $pdo->prepare($totalcostsupdate);
			$totalcostsupdate->execute();
		}
?>

