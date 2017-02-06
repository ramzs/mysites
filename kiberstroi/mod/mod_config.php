<? 
//$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
session_start();
// подключаем основные функции и классы
require_once("mod_func.php");
require_once($_SERVER['DOCUMENT_ROOT']."/admin/db.php");

$Db=new Db ($DBServer,$DBLogin,$DBPassword,$DBName);
$Db->connect();
mysql_query("SET NAMES 'cp1251'");
// глобальные настройки для модулей
$Db->query="SELECT `mod`,`option`,`value` FROM `mod_config`";
$Db->query();
while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $config[$lRes[mod]][$lRes[option]] = $lRes[value];

// проверям есть ли ограничения доступа к сайту 
if (!empty($config["main"]["allow_ip"])) 
	{ 
	$array_ip = explode(",",$config["main"]["allow_ip"]);
	$you_ip = RealIP();
	if (!in_array($you_ip, $array_ip)) 
		{
		include "close.html";
		die();
		}
	}

// вытаскиваем модули
$Db->query="SELECT `id_mod`, `name_mod` FROM `modules` WHERE `act_mod`='1' AND `act_admin_mod`='1' AND `view_site`='1'";
$Db->query();
while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $pages[] = $lRes[name_mod];

// вытаскиваем баннеры
$Db->query="SELECT `source`,`cat` FROM `mod_banners` WHERE `act`='1'";
$Db->query();
if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $banners[$lRes["cat"]][] = str_replace("../upload/","/upload/",stripslashes($lRes["source"]));

if ($_SESSION[autorized]!=0)  {
	$autorized=1;
	$global_user=$_SESSION['id_person'];
	$Db->query="SELECT * FROM `mod_person` WHERE `id_person`='".$_SESSION['id_person']."' LIMIT 1";
	$Db->query();
	$global_res=mysql_fetch_assoc($Db->lQueryResult);
}
else $autorized=0;
			

?>