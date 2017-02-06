<?php
header('Content-Type: text/html; charset= windows-1251');
$subject = 'Тестовое сообщение';
$message = 'Здравствуйте! Это тестовое сообщение';
		
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=windows-1251\r\n";
$headers .= "From: Kiberstroi <mail@kiberstroi.ru>\r\n";


if (mail("karrie-info@mail.ru", $subject, $message, $headers)) echo "Отправлено на почту karrie-info@mail.ru в ".date("H:i:s"); else echo "Не отправлено"; 
?>