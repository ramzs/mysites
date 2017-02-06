<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","mod_catalog_cat","edit_cat","delete","del_cat","config","del_person_img","pereschet");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = 15; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_online) FROM mod_online"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_online)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			$Db->query="SELECT * FROM `mod_online` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=online&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td width="150">Дата</td><td>Отзыв</td><td width="80"></td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					if (!empty($lRes["answer"])) $answer = $lRes["answer"]; else $answer = "Нет";
					
					$content_mod.= '<tr class="one_news">
								<td>'.formatedpost($lRes["date"], false).'</td>
								<td><a href="index.php?mod=online&action=edit&id='.$lRes["id_online"].'">'.substring($lRes["quest"]).'</a></td>
								<td></td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_online']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_online']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_online']."]' class='checkboxact' />";


		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_online']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
				}
				
				for ($i=1; $i<=$total; $i++) if ($page!=$i) $navi.='<a href=index.php?mod=online&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
				
				$content_mod.= '<tr><td></td><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
				<script type="text/javascript">
				$(function () {
				
					$("#selall").live("click", function () {
						if (!$("#selall").is(":checked")){
							$(".checkbox").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkbox").attr("checked", true);
							$.uniform.update();
						}	
					});
					$("#selall_act").live("click", function () {
						if (!$("#selall_act").is(":checked")){
							$(".checkboxact").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkboxact").attr("checked", true);
							$.uniform.update();
						}	
					});
				});
			</script>
			<div class="conf"><input type="checkbox" value="1" class="checkboxact" id="selall_act" /><input type="checkbox" value="1" class="checkbox" id="selall" /></div></td></tr></table></form>';
				
				if ($total > 1)	$content_mod.="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";
			}
			else
			{
				$content_mod.= "Отзывов нет.";
			}
}
else
{

	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `mod_online` WHERE `id_online` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_online SET act = '".$val."' WHERE id_online ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=online&action=list'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_online` WHERE `id_online`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if (empty($lRes["date"])) { $date = date("m/d/Y"); $time=date("H:i:s");} else { $date = date("m/d/Y", strtotime($lRes["date"])); $time=date("H:i:s", strtotime($lRes["date"])); }
			$content_mod.= '<form method="post" enctype="multipart/form-data"> 
			<h4>Отзыв от '.$lRes["name_online"].':</h4><br />
			<textarea name="answer" />'.$lRes["quest"].'</textarea>
			<input type="hidden" name="id" value="'.$id.'"><br />
				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="40%"><p>Дата:</p></td>
					<td width="60%">
					<input type="text" name="date" value="'.$date.'">
					<span class="small">(месяц, день, год)</span></td>
				</tr>
			</table>
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			$answer = mysql_escape_string(trim($_POST["answer"]));
			$id = $_POST["id"];	
			$datemas = array(); 
   			$datemas = explode("/", htmlspecialchars($_POST['date']));
			$date=$datemas[2]."-".$datemas[0]."-".$datemas[1]." ".$_POST['time'];
			$Db->query="INSERT INTO `mod_online` (`id_online`, `answer`, `date`)
						VALUES ('".$id."','".$answer."','".$date."')
						ON DUPLICATE KEY UPDATE
						`id_online`=VALUES(`id_online`),
						`answer`=VALUES(`answer`),
						`date`=VALUES(`date`)";
						
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=online&action=list'></head></html>");
		}
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
				$content_mod.= '<p>Настройки для данного модуля не найдены.</p>';
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