<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("delete","config");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = $config["search"]["num_in_admin"]; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_search) FROM mod_search"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_search)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			
			$Db->query="SELECT * FROM `mod_search` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $content_mod.= formatedpost($lRes["date"]).' - '.$lRes["query"].'<br />';
				// Проверяем нужны ли стрелки назад
				if ($page != 1) $pervpage = '<a href="index.php?mod=search&page='. ($page - 1) .'"><< Предыдущая страница</a>';
				// Проверяем нужны ли стрелки вперед
				if ($page != $total) $nextpage = '<a href="index.php?mod=search&page='. ($page + 1) .'">Следующая страница >></a>';
				if ($total > 1)	$navigation="<table class=\"pstrnav\"><tr><td class='last'>".$pervpage."</td><td class='all_page'>Вы находитесь на странице: $page, всего страниц: $total</td><td class='next'>".$nextpage."</td></tr></table>";
				$content_mod.= "</table>".$navigation;
			}
			else
			{
				$content_mod = "Запросов нет.";
			}
}
else
{
	if ($action=="delete")
	{
        $Db->query="TRUNCATE TABLE `mod_search`"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=search&action=list'></head></html>");
	}	
}
if ($action=="config")
	{
		if (!@$_POST["save"])
		{
			$Db->query="SELECT * FROM `mod_config` WHERE `mod`='".$mod."'";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
			$content_mod.= "<form action='index.php?mod=".$mod."&action=config' method='post'>";
				while($lRes=mysql_fetch_assoc($Db->lQueryResult))
				{
				if($lRes[type]=="checkbox") $content_mod.= '<input type="hidden" name="'.$lRes[option].'" value="0">';
				if($lRes[type]=="checkbox" && $lRes["value"]=="on") $chek = ' checked="checked"'; else $chek = '';
				if($lRes[type]=="text") $val = ' value="'.$lRes[value].'"'; else $val = '';
				$content_mod.= '<p><input class="check" name="'.$lRes[option].'" type="'.$lRes[type].'"'.$chek.$val.' /> '.$lRes[name].'</p>';
				}
			$content_mod.= '<p><input type="submit" value="Обновить" class="but" name="save"></p>
			</form>';
			}
			else
			{
				$content_mod.= '<br /><p>Настройки для данного модуля не найдены.</p>';
			}
		}
		else
		{
			// обрабатываем форму сохранения настроек
			unset($_POST[save]);
			$query = '';
			foreach ($_POST as $key=>$value) $query.= " WHEN `option`='".$key."' THEN '".$value."'";	
			$Db->query="UPDATE `mod_config` 
			SET `value` = CASE ".$query."
			ELSE `value` END";
			$Db->query(); 
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=".$mod."&action=config'></head></html>");
		}
	}

echo $content_mod;

// необходимые функции для этого модуля
?>