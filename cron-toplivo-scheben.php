<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'bdcon.php';

$emails = [];
require_once 'includes/phpmailer/RBMailer.php';
$mail = new \PHPMailer\PHPMailer\RBMailer();
try {
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }
    $mail->Subject = "Отчет по топливу и щебню";
    $mail->setMessageContent('gsm-scheben', []);
    $mail->send();
} catch (Exception $e) {
    return;
}
