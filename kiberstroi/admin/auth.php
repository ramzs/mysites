<? 
// глобальная авторизация
if  (empty($_SESSION['id_user']) or empty($_SESSION['hash_user']) or empty($_SESSION['access_user'])) {$autorized = 0;
	unset($_SESSION['id_user']);
	unset($_SESSION['hash_user']);
	unset($_SESSION['access_user']);
} else { 
$filter = new filter;
$global_user = $filter->html_filter($_SESSION['id_user']);
$hash = $filter->html_filter($_SESSION['hash_user']);
$access = $filter->html_filter($_SESSION['access_user']);

$Db->query="SELECT `user_hash`,`name_user`,`date` FROM `mod_users_admin` WHERE `id_user`='".$global_user."' LIMIT 1";
$Db->query();
$lRes=mysql_fetch_assoc($Db->lQueryResult);
	if ($lRes["user_hash"]!=$hash) 
	{
	$autorized = 0;
	unset($_SESSION['id_user']);
	unset($_SESSION['hash_user']);
	unset($_SESSION['access_user']);
	}
	else 
	{$autorized = 1; $status = $lRes["name_user"]; $mydate = formatedpost($lRes["date"]);}
}
// глобальные настройки для модулей
$Db->query="SELECT `mod`,`option`,`value` FROM `mod_config`";
$Db->query();
while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $config[$lRes[mod]][$lRes[option]] = $lRes[value];
?>