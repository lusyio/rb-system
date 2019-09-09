<?php
if(isset($_POST['idreport']) && !empty($_POST['idreport'])) {
	
	// подключаем pdo
	require_once(realpath('bdcon.php'));
	
	// кладем запрос в переменную
	$report = $_POST['idreport'];
	$datestart = $_POST['datestart'];
	$dateend = $_POST['dateend'];
	$iduser = $_POST['iduser'];
	
	$filename = 'includes/report/'.$report.'.php';
	
	if (file_exists($filename)) {
		echo '<div class="info-block"><div class="inside">';
		require_once(realpath('includes/report/'.$report.'.php'));
		echo '</div></div>';
	} else {
		 echo '<p class="text-center mt-5">Получен запрос на формирование отчета '.$report.' по датам с '.$datestart.' по '.$dateend.', но файла для его формирования нет.</p>';
	}

}
if(isset($_POST['technorm']) && !empty($_POST['technorm'])) {
	// подключаем pdo
	require_once(realpath('bdcon.php'));
	$tech = $_POST['technorm'];
	$report = $_POST['info'];
	$filename = 'includes/report/'.$report.'.php';
	require_once(realpath('includes/tech/'.$report.'.php'));
} 

if(isset($_POST['zap']) && !empty($_POST['zap'])) {
	// подключаем pdo
	require_once(realpath('bdcon.php'));
	$zap = $_POST['zap'];
	$count = $_POST['count'];
	$filename = 'includes/tech/tech-ajax-zap.php';
	require_once(realpath('includes/tech/tech-ajax-zap.php'));
}
if(isset($_POST['techreport']) && !empty($_POST['techreport'])) {
	// подключаем pdo
	require_once(realpath('bdcon.php'));
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $idtech = $_POST['techreport'];
	$filename = 'includes/tech/tech-ajax-zap.php';
	require_once(realpath('includes/tech-tech-table.php'));
}
?>