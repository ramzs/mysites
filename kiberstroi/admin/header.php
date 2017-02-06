<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Система управления сайтом <?=$DomenName?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="screen" type="text/css" href="colorpicker.css" />
	<link rel="stylesheet" media="screen" type="text/css" href="layout.css" />
	<link rel="stylesheet" media="screen" type="text/css" href="datepicker.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700&subset=cyrillic' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
</head>
<body id="main">