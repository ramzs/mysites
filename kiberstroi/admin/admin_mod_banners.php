<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","config","scroll","pereschet","delete_scroll_img","editscroll","deletescroll","listscroll");
			parse_str($_SERVER['QUERY_STRING']);
			$ban_array = array(1=>"Дескриптор");
			
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			
			$Db->query="SELECT `name`,`id_ban`,`act`,`cat` FROM `mod_banners` ORDER BY `id_ban` DESC";
			$Db->query();
			$content_mod = "";
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod = '<form method="post" action="index.php?mod=banners&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td width="100">Расположение</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=banners&action=edit&id='.$lRes["id_ban"].'">'.$lRes["name"].'</a></td>
								<td>'.$ban_array[$lRes["cat"]].'</td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_ban']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_ban']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_ban']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_ban']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				$content_mod.= '<tr><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
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
			}
			else
			{
				$content_mod.= "Баннеров нет.";
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
			 $Db->query="DELETE FROM `mod_banners` WHERE `id_ban` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_banners SET act = '".$val."' WHERE id_ban ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=list'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_banners` WHERE id_ban='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";

			
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название (для отображения в админке):</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="80"> 						</td>
  			</tr>
			</table>

  			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br /><br />
			<p>Содержимое</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['source'])).'</textarea> 
			<input type="hidden" name="id" value="'.$id.'"><br />
			<p>Расположение <select name="cat">';
			foreach ($ban_array as $key=>$value) if ($key!=$lRes[cat]) $content_mod.= '<option value="'.$key.'">'.$value.'</option>'; else $content_mod.= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
			$content_mod.= '</select></p>
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$text = mysql_escape_string(trim($_POST["text"]));
			$id = $_POST["id"];
			$anchor = trans($name);
			if (@$_POST["act"]) $act = 1; else $act = 0;
			if (@$_POST["all"]) $all = 1; else $all = 0;
			$Db->query="INSERT INTO `mod_banners` (`id_ban`, `name`, `source`, `act`, `cat`)
						VALUES ('".$id."','".$name."','".$text."','".$act."','".$_POST['cat']."')
						ON DUPLICATE KEY UPDATE
						`id_ban`=VALUES(`id_ban`),
						`name`=VALUES(`name`),
						`source`=VALUES(`source`),
						`act`=VALUES(`act`),
						`cat`=VALUES(`cat`)";
						
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=list'></head></html>");
		}
	}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_banners` WHERE `id_ban` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners'></head></html>");
	}
	if ($action=="delete_scroll_img")
	{
		 $Db->query="SELECT `img`,`imgsm` FROM `scroll` WHERE `id_scroll` = ".$id;
		 $Db->query();
		 if (mysql_num_rows($Db->lQueryResult)>0) {
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			unlink($_SERVER['DOCUMENT_ROOT'].$lRes["img"]);
			unlink($_SERVER['DOCUMENT_ROOT'].$lRes["imgsm"]);
		 }
	
        $Db->query="DELETE FROM `scroll` WHERE `id_scroll` = '".$id."'";
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=scroll'></head></html>");
	}
	if ($action=="config")
	{
		if (!@$_POST["save"])
		{
			$content_mod = '<h4>Настройки модуля:</h4>';
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
	if ($action=="scroll")
	{
		if (!@$_POST["save"])
		{
				$content_mod = '<br /><form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=banners&action=scroll">';	
				$Db->query="SELECT `id_scroll`,`imgsm` FROM `scroll`";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
					while($lRes=mysql_fetch_assoc($Db->lQueryResult))
						{
						$content_mod.= '<img src="'.$lRes["imgsm"].'" height="30" hspace="5" align="middle" /><a title="Удалить" href="#" OnClick="Delete_scroll_img('.$lRes["id_scroll"].')"><img src="images/act_no.jpg" border="0" /></a>';
						}
					}
					else
					{
					$content_mod.= "Файлов нет";
					}
				$content_mod.= '<br /><br />Оптимальная высота - 260px, ширина - 1200px, формат - jpg<br /><input type="file" name="image" />
				<p><input type="submit" value="Сохранить" class="but" name="save"></p>
			</form> ';
		}
		else
		{
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myname = rand();
					$myname_full = "/upload/scroll/sm".$myname.".jpg";
					$myname_full_large = "/upload/scroll/".$myname.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$myname_full, $thumb_width=239, $thumb_height=30, $do_cut=true);
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$myname_full_large, $thumb_width=1200, $thumb_height=260, $do_cut=true);

					$Db->query="INSERT INTO scroll (imgsm,img) VALUES('".$myname_full."', '".$myname_full_large."')";
					$Db->query();
					exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=scroll'></head></html>");	
					}	
		}
	}
	/*
	if ($action=="deletescroll")
	{
        $Db->query="SELECT * FROM `scroll` WHERE `id_scroll` = '".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="DELETE FROM `scroll` WHERE `id_scroll` = '".$id."'"; 
		$Db->query();
		unlink($_SERVER['DOCUMENT_ROOT']."".$lRes["imgsm"]."");
		unlink($_SERVER['DOCUMENT_ROOT']."".$lRes["img"]."");

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=listscroll'></head></html>");
	}	
	if ($action=="listscroll")
	{
		$Db->query="SELECT * FROM `SCROLL` ORDER BY `id_scroll`";
			$Db->query();
			$content_mod = "";
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod = '<form method="post" action="index.php?mod=banners&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td></tr>';
				
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=banners&action=editscroll&id='.$lRes["id_scroll"].'">'.$lRes["id_scroll"].'</a>
								<a href="index.php?mod=banners&action=deletescroll&id='.$lRes["id_scroll"].'"><img src="images/act_no.jpg" border="0" /></a>
								</td>
								</tr>';
								$num++;
				}
				$content_mod.= '<tr><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
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
			</td></tr></table></form>';
			}
			else
			{
				$content_mod.= "Скроллер пуст.";
			}
	}	
	if ($action=="editscroll")
	{
		if (!@$_POST["addscroll"]) 
		{ // если не нажата кнопка
			$Db->query="SELECT * FROM `scroll` WHERE `id_scroll`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform">
				<p>Содержимое</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text'])).'</textarea> 
				<br />Оптимальная высота - 252px, ширина - 726px, формат - jpg<br />
				<input type="hidden" name="oldimg" value="'.$lRes['img'].'">
				<input type="hidden" name="oldimgsm" value="'.$lRes['imgsm'].'">
				<img src="'.$lRes['imgsm'].'"><br />
				<input type="file" name="image" />
				<p><input type="submit" value="Сохранить" class="but" name="addscroll"></p>
				</form>';	
		}
		else //обрабатываем форму
		{
			$myname_full=$_POST["oldimgsm"];
			$myname_full_large=$_POST["oldimg"];
			if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myname = rand();
					$myname_full = "/upload/scroll/sm".$myname.".jpg";
					$myname_full_large = "/upload/scroll/".$myname.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$myname_full, $thumb_width=239, $thumb_height=30, $do_cut=true);
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$myname_full_large, $thumb_width=726, $thumb_height=252, $do_cut=true);
			}

			$Db->query="INSERT INTO `scroll` (`id_scroll`, `imgsm`, `img`, `text`)
						VALUES ('".$id."','".$myname_full."','".$myname_full_large."','".$_POST[text]."')
						ON DUPLICATE KEY UPDATE
						`id_scroll`=VALUES(`id_scroll`),
						`imgsm`=VALUES(`imgsm`),
						`img`=VALUES(`img`),
						`text`=VALUES(`text`)";
				
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=banners&action=listscroll'></head></html>");	
			
		}
	}
	*/
}


echo $content_mod;

// необходимые функции для этого модуля
?>