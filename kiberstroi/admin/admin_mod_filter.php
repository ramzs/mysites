<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","edit_filter","delete","delete_filter");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля
			
			$Db->query="SELECT * FROM `filter` ORDER BY `id_filter`";
			$Db->query();
			$rank = mysql_num_rows($Db->lQueryResult);
			if ($rank>0) {
				$content_mod = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr class="head_news">
								<td>Параметр</td>
								<td width="50"></td>
								</tr>';
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=filter&action=edit&id='.$lRes["id_filter"].'">'.$lRes["name_filter"].'</a></td>
								<td>
								<a href="#" onclick="Delete_filter('.$lRes["id_filter"].')" title="Удалить параметр"><img src="images/del.png" class="pnghack" /></a>
								</td>
								</tr>';
								$num++;
				}
			}
			else
			{
				$content_mod = "<br />Параметров нет.";
			}
}
else
{
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `filter` WHERE `id_filter`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$content_mod = "
			<img src='images/left.jpg' alt='' align='middle' /> <a href='index.php?mod=filter' class='red'>Вернуться назад</a><div class='clear'></div>";
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=filter&action=edit&id='.$id.'"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название параметра:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_filter'])).'" size="50"> 						</td>
  			</tr>
			</table>
			<input type="hidden" name="id" value="'.$id.'">
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
			if ($id!="new") {
			$content_mod.= '<hr /><div class="add"><a href="index.php?mod=filter&action=edit_filter&id=new&cat='.$id.'" class="red"><img src="images/plus.png" class="pnghack" align="middle" border="0" /> Добавить новое</a></div><h4><strong>Список параметра:</strong></h4>';
				$Db->query="SELECT `id_params`,`name_params` FROM `filter_params` WHERE `filter_rel`='".$id."' ORDER BY `name_params`";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
					$content_mod.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr class="head_news">
								<td>Название</td>
								<td width="50"></td>
								</tr>';
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=filter&action=edit_filter&id='.$lRes["id_params"].'">'.$lRes["name_params"].'</a></td>
								<td>
								<a href="#" onclick="Delete_filter_params('.$lRes["id_params"].')" title="Удалить"><img src="images/del.png" class="pnghack" /></a>
								</td>
								</tr>';
						}
						$content_mod.= "</table>";
					}
					else
					{
					$content_mod.= "Список пуст";
					}
			}

		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$id = $_POST["id"];
			$Db->query="INSERT INTO `filter` (`id_filter`, `name_filter`)
						VALUES ('".$id."','".$name."')
						ON DUPLICATE KEY UPDATE
						`id_filter`=VALUES(`id_filter`),
						`name_filter`=VALUES(`name_filter`)";
			$content_mod.= "<br /><br /><p align='center'><img src='/images/loader.gif' /></p>";
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=filter'></head></html>");
			
		}
	}
		if ($action=="edit_filter")
		{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `filter_params` WHERE `id_params`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($id!="new") $catnow = $lRes['filter_rel']; else $catnow = $cat;
			$content_mod = "
			<img src='images/left.jpg' alt='' align='middle' /> <a href='index.php?mod=filter&action=edit&id=".$catnow."' class='red'>Вернуться назад</a><div class='clear'></div>";
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=filter&action=edit_filter&id='.$id.'"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_params'])).'" size="50"> 						</td>
  			</tr>
			</table>
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="cat" value="'.$catnow.'">
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$text = mysql_escape_string($_POST["text"]);
			$id = $_POST["id"];
			$cat = $_POST["cat"];
			$Db->query="INSERT INTO `filter_params` (`id_params`, `name_params`, `filter_rel`)
						VALUES ('".$id."','".$name."','".$cat."')
						ON DUPLICATE KEY UPDATE
						`id_params`=VALUES(`id_params`),
						`name_params`=VALUES(`name_params`),
						`filter_rel`=VALUES(`filter_rel`)";
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=filter&action=edit&id=".$cat."'></head></html>");
			
		}
		}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `filter` WHERE `id_filter` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=filter'></head></html>");	
	}
	if ($action=="delete_filter")
	{
		$Db->query="SELECT `filter_rel` FROM `filter_params` WHERE `id_params`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$filter_rel = $lRes["filter_rel"];
		
        $Db->query="DELETE FROM `filter_params` WHERE `id_params` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=filter&action=edit&id=".$filter_rel."'></head></html>");
	}
}


echo $content_mod;
// необходимые функции для этого модуля
?>