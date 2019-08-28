<?php
$updatework = 'UPDATE tech_oil SET count = "'.$count.'" WHERE id = "'.$zap.'"';
$updatework = $pdo->prepare($updatework);
$updatework->execute();
echo 'done';
?>