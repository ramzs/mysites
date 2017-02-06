<?php
$result = mail("smgroup-48@mail.ru","Заявка с сайта avtoprokat-48.ru","\nИмя: $_POST[name] \nTel: $_POST[phone]", "Content-type: text/plain; charset=\"utf-8\"\r\n");

?>