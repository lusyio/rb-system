<?php
$arr = ['января', 'февраля','марта', 'апреля', 'мая', 'июня','июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
$day = date("j", strtotime($now));
$month2 = (date("n", strtotime($now)))-1;
?>
<div class="today pt-2">
	<h4>Сегодня <?=$day.' '.$arr[$month2]?></h4>
	<?php include 'today/otgryz.php';?>
	<?php if ($iduser != 20) { 
		include 'today/scrap.php';
		 } ?>
	<?php include 'today/info.php';?>
	
</div>