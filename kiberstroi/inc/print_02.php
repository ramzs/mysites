<?
if (!@$_GET["id"] or !@$_GET["key"]) die ("Access denied");
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
$filter = new filter;
$key = $filter->html_filter($_GET["key"]);  
$order = base64_decode($filter->html_filter($_GET["id"]));
$c = $filter->html_filter($_GET["c"]);

if (!empty($key) && !empty($order) && $c==base64_encode($_SESSION["hash"]."|".$_SESSION['access']))	// 								
	{								
					
					$Db->query="SELECT * FROM `mod_order` WHERE `id_order`='".$order."' LIMIT 1";
					$Db->query();
					$lRes=mysql_fetch_assoc($Db->lQueryResult);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Счет</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style>
body {
	font-family:Arial, Helvetica, sans-serif;
	font-size:16px;
}
table td {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding-left:5px;
	border:1px;
}
.title{
	font-weight:bold;
	font-size:24px;
	margin:15px 0;
	text-align:center;
	padding-bottom:10px;
}
.title2{
	font-weight:bold;
	font-size:16px;
	margin:15px 0;
	padding-bottom:10px;
	text-align:center;
}
.info{
	font-weight:bold;
}
.tovars{
	width:100%;
	border-collapse:collapse;
	border:2px solid #000;
}
.tovars th{
	text-align:center;	
	padding:1px;
	border:1px solid #000;
}
.tovars td{		
	padding:1px;
	border:1px solid #000;
}
.users td{
	margin:0 10px;
	text-align:center;
}

.users tr.bord td div{
	border-top:1px solid #000;
	margin:0 10%;
	text-align:center;
}

.wrap_users {
	position: relative;
}

.bill_mp {
	position: absolute;
	left: 170px;
	top: -65;
	z-index: -10;
}
.bill_mp2 {
	position: absolute;
	left: 600px;
	top: -45;
	z-index: -10;
}
.wrap_users_zindex {
	position: relative;
	z-index: 999;
}
.inright {text-align:right; padding-right:5px;}
.incenter {text-align:center;}
.basket_head td {font-weight:bold; text-align:center; line-height:25px; padding-left:0;}
.whiteborder {border:1px solid #000 ;  }
</style>
</head>
<body bgColor="#ffffff" onLoad="window.print();">
<div style="width:800px;">
<br><br>
<strong>Продавец:</strong> <?=$config["order"]["compname"]?><br />
Адрес: <?=$config["order"]["yur_adress"]?><br />
ИНН: <?=$config["order"]["inn"]?><br />
КПП: <?=$config["order"]["kpp"]?><br />
Рассчетный счет: <?=$config["order"]["schet"]?><br />
Кор. счет: <?=$config["order"]["korschet"]?><br />
БИК: <?=$config["order"]["bik"]?><br />
Банк: <?=$config["order"]["bankname"]?><br />
<br>
<strong>Заказчик:</strong> <? if(empty($lRes["yur_rek"])) echo $lRes["name"].", ".$lRes["adress"]; else echo $lRes["yur_name"].", ".$lRes["yur_rek"];?>

<div class="title">Счет № <?=$lRes["id_order"]?>/2 от <?=date("d.m.Y", strtotime($lRes["date"]))?>г.</div>

	<?=$lRes["list"]?>

<p style="font-size:16px">Сумма прописью: <?

$slovami = Suma($lRes["summ"]);

echo bukvica($slovami);

?></b></p>
<br><br><br>
<div class="wrap_users">
<div class="bill_mp"><img src="/images/podpis_project.jpg"></div>
<div class="bill_mp2"><img src="/images/podpis_project2.jpg"></div>
</div>
<div class="wrap_users_zindex">
<table cellpadding="0" cellspacing="0" width="100%" class="users">
	<tr>
		<td width="10%"><b>Руководитель</b></td>               
		<td  align="center">_____________________________<br><strong><?=$config["order"]["gendir"]?></strong></td>    
        <td width="10%"><b>Бухгалтер</b></td>    
        <td>_____________________________<br><strong><?=$config["order"]["glavbuh"]?></strong></td>    		
	</tr>
</table>
</div>
</div>
</div>
</body>
</html>

<? 
	}
	else echo 'Неверные параметры.';
	?>