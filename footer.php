<?php
	if (!empty($iduser)) {
 	$activity = $pdo->prepare('UPDATE `usertbl` SET activity = :activity where id="'.$iduser.'"');
 	$activity->execute(array('activity' => $datetime));
 	}
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</body>
</html>

