<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'bdcon.php';

//$emails = ['mr-kelevras@yandex.ru'];
$emails = ['mr-kelevras@yandex.ru','fso20061@yandex.ru'];
require_once 'includes/phpmailer/RBMailer.php';
$mail = new \PHPMailer\PHPMailer\RBMailer();
try {
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }
    $date = date('d.m');
    $mail->Subject = "Отчет " . $date;
    $mail->setMessageContent('report', []);
    $mail->send();
} catch (Exception $e) {
    return;
}
