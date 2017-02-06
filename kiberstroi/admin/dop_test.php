<? 
include $_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php";
$Db->query="SELECT `name_goods` FROM `mod_catalog`";
$Db->query();
while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $existing_users[] = pass_solt($lRes["name_goods"]);
$filter = new filter; 
$user_name = $filter->html_filter($_GET['code']);
if (in_array($user_name, $existing_users))
{
	echo "true";
} 
else
{
	echo "false";
}

?>