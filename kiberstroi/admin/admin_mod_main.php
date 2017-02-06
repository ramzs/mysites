<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");
	
exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content&action=list'></head></html>");	
	
	/*$Db->query="SELECT count(id_goods) FROM `mod_catalog`";
	$Db->query();
	if (mysql_num_rows($Db->lQueryResult)>0) 
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$count_goods = $lRes["count(id_goods)"];
	
	$Db->query="SELECT count(id_cat) FROM `mod_catalog_cat`";
	$Db->query();
	if (mysql_num_rows($Db->lQueryResult)>0) 
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$count_cat = $lRes["count(id_cat)"];
	
	$Db->query="SELECT count(id_order) FROM `mod_order`";
	$Db->query();
	if (mysql_num_rows($Db->lQueryResult)>0) 
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$count_order = $lRes["count(id_order)"];
		
	$content_mod = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="60%" valign="top">
						<h4>Лента событий:</h4><br />';
						
	$Db->query="SELECT name,date FROM `mod_stat` ORDER BY date DESC LIMIT 15";
	$Db->query();
	if (mysql_num_rows($Db->lQueryResult)>0) 
	{
		while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
		{
			$content_mod.= '<div class="event">'.formatedpost($lRes["date"]).' :: '.$lRes["name"].'</div>';
		}
	} else $content_mod.= 'Событий нет';
	$content_mod.= 
					  '<td valign="top">
						<h4>Общая статистика:</h4><br />
						<p>Заказов: '.$count_order.'</p><br />
						<p>Категорий: '.$count_cat.'</p><br />
						<p>Товаров: '.$count_goods.'</p><br />
						</td>
					  </tr>
					</table>
					';



echo $content_mod; */

// необходимые функции для этого модуля
?>