<?php 
session_start();
$_SESSION["session_username"] = $_COOKIE["session_username"];
$iduser = $_COOKIE["iduser"];
if(!isset($_SESSION["session_username"])) {
	header("location:/login.php");
} else { ?>
<html>
<head>
    <title>Рубеж</title>
<?php include 'bdcon.php'; include 'header.php'; 
	
	$activity = DBOnce('activity','usertbl','id='.$iduser);
	if ($activity <= '2019-04-01 13:59:18') {
		echo '<script>
$(document).ready(function() {
document.location.href = "http://rubezh-info.ru/logout.php";
});
</script>';
	}
	if (!empty($_GET['folder'])) {
	$folder = $_GET['folder']; // принимаем id задачи
	
	$folders = array('log', 'report', 'conf', 'vesylive', 'sclad', 'gsm', 'kassad', 'kassav','brigada','brigada-report','bankclient','vesy-report','tech-work','tech-mech','tech-tech','reg','tech-report','tech','oil','tech-to','tech-sclad','tech-add');
	if (!empty($folder)) {
		if (in_array($folder, $folders)) {
			$filename = 'includes/'.$folder.'.php';
			if (file_exists($filename)) {
			    include 'includes/'.$folder.'.php';
			} else {
			    echo 'Нет файла для отображения информации';
			}
		} 
	}
	} else {
	
	// тест 
	if ($iduser == 1232) {
		$arr = ['test','smenanow','widj-kop','widg-poboch','widg-gsm','widj-otgryz','widj-rez','main'];
	}
	// Админ
	if ($iduser == 1) { $arr = ['main'];   }
	
	// Ласковский С.В.
	if ($iduser == 2) { $arr = ['main']; }
	
	// Евдокимов
	if ($iduser == 4) { $arr = ['widg-poboch','widg-gsm']; }
	
	// Чернюк
	if ($iduser == 5) { $arr = ['smenanow','widg-poboch','widj-otgryz','widj-rez']; }
	
	// Двореченец
	if ($iduser == 6) { $arr = ['widj-otgryz','widg-poboch']; }
	
	// Соколов
	if ($iduser == 7) { $arr = ['widg-poboch']; }
	
	// Корнеев
	if ($iduser == 8) { $arr = ['smenanow','widg-poboch']; }
	
	// Бригадиры
	if ($iduser == 9 or $iduser == 10 or $iduser == 21 or $iduser == 23 or $iduser == 24) { $arr = ['smenanow','widg-poboch']; }
	
	// Саида
	if ($iduser == 18 or $iduser == 26) { $arr = ['widj-otgryz','widg-poboch']; }
	
	// Канаев
	if ($iduser == 12) { $arr = ['widg-poboch']; }
	
	// Влада
	if ($iduser == 13) { $arr = ['widj-otgryz','widg-poboch']; }
	
	// Карпунин
	if ($iduser == 14) { $arr = ['widj-otgryz','widg-poboch','widg-gsm']; }
	
	// Красовский
	if ($iduser == 17) { $arr = ['widj-kop']; }
	
	// Филлипович
	if ($iduser == 20) { $arr = ['main']; }
	 
	// Седышев
	if ($iduser == 25) { $arr = ['widg-poboch']; }
	foreach ($arr as $elem)
	{
		$filename = 'includes/dashboard/'.$elem.'.php';
		if (file_exists($filename)) {
		    include 'includes/dashboard/'.$elem.'.php';
		} else {
		    echo '! Виджет '.$elem.' не найден<br>';
		}
	}
	}


include 'footer.php';
}
?>