<?php
header('Content-Type: text/html; charset= windows-1251');
$subject = '�������� ���������';
$message = '������������! ��� �������� ���������';
		
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=windows-1251\r\n";
$headers .= "From: Kiberstroi <mail@kiberstroi.ru>\r\n";


if (mail("karrie-info@mail.ru", $subject, $message, $headers)) echo "���������� �� ����� karrie-info@mail.ru � ".date("H:i:s"); else echo "�� ����������"; 
?>