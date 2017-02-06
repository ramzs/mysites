<? 
if (!@$_GET["id"] or !@$_GET["key"]) die ("Access denied");
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
$filter = new filter;
$key = $filter->html_filter($_GET["key"]);  
$order = base64_decode($filter->html_filter($_GET["id"]));
$c = $filter->html_filter($_GET["c"]);
$session = base64_decode($c);
$sess_array = explode("|",unserialize($session));

if (!empty($key) && !empty($order) && is_numeric($order) && $key==pass_solt($sess_array[0]))	// 								
	{				
		$Db->query="SELECT * FROM `mod_order` LEFT JOIN `mod_person` ON (mod_person.id_person=mod_order.rel_person) WHERE `rel_person`='".$sess_array[1]."' AND `id_order`='".$order."' LIMIT 1";
		$Db->query();		
			if (mysql_num_rows($Db->lQueryResult)>0) 
				{
					$lRes=mysql_fetch_array($Db->lQueryResult);
					echo '<div class="listorder">';
					echo '<h5>Лист заказа:</h5>'.$lRes["list"];
					echo '</div>';
				}
				else echo 'Неверные параметры.';
	}
	else echo 'Неверные параметры.';
?>