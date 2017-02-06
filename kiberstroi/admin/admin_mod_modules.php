<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array();
			parse_str($_SERVER['QUERY_STRING']);
			$need_acess=pass_solt("0");

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля
	if ($access==$need_acess) {
			if (!@$_POST["save"])
		{
			$content_mod = '<h4>Подключенные модули:</h4>';
			$Db->query="SELECT * FROM `modules`";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
			$content_mod.= "<form action='index.php?mod=modules' method='post'>";
				while($lRes=mysql_fetch_assoc($Db->lQueryResult))
				{
				if($lRes["act_admin_mod"]=="1") $chek = ' checked="checked"'; else $chek = '';
				$content_mod.= '<input type="hidden" name="'.$lRes[name_mod].'" value="0">';
				$content_mod.= '<p><input class="check" name="'.$lRes[name_mod].'" type="checkbox"'.$chek.' /> '.$lRes[title_mod].'</p>';
				}
			$content_mod.= '<p><input type="submit" value="Обновить" class="but" name="save"></p>
			</form>';
			}

		}
		else
		{
			// обрабатываем форму сохранения настроек
			unset($_POST[save]);
			$query = '';
			$active = array("on"=>1,"0"=>0);
			foreach ($_POST as $key=>$value) $query.= " WHEN `name_mod`='".$key."' THEN '".$active[$value]."'";	
			$Db->query="UPDATE `modules` 
			SET `act_admin_mod` = CASE ".$query."
			ELSE `act_admin_mod` END";
			$Db->query(); 
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=modules'></head></html>");
		}
	}
}


echo $content_mod;
// необходимые функции для этого модуля
?>