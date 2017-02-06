<? 
session_start();
if  (empty($_SESSION['id_user']) or empty($_SESSION['hash_admin']) or empty($_SESSION['access_admin']))  die ("Access denied");
require_once($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");
$filter = new filter; 
$order = $filter->html_filter($_GET["id"]);

if (!empty($order) && is_numeric($order))	// 								
	{								
		$Db->query="SELECT * FROM `mod_order` LEFT JOIN `mod_person` ON (mod_person.id_person=mod_order.rel_person) WHERE  `id_order`='".$order."' LIMIT 1";
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