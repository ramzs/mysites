<?
require_once("mod/mod_config.php");
if (!isset($_GET)) { exit("Не удалсь подключится к серверу.");} else {
	$rec_url=$_SERVER['REQUEST_URI'];
	$param=explode("/",$rec_url);
	$param=Clear_array($param);
	$filter = new filter; 
	$mod = $filter->html_filter(@$param[0]); 
	}
if (!isset($mod) || ($mod=="") || (!in_array($mod, $pages)) ) if (in_array("content", $pages)) $mod="content"; else die ("Access denied (ind)");
include("mod/mod_$mod.php");
?>