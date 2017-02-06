<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_img","config","pereschet","pereschet_cat","list_cat","edit_cat");
			parse_str($_SERVER['QUERY_STRING']);
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = 15; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_news) FROM mod_news"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_news)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			
			$Db->query="SELECT * FROM `mod_news` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=news&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td width="130">Дата выхода</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {

					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=news&action=edit&id='.$lRes["id_news"].'">'.$lRes["name"].'</a></td>
								<td>'.formatedpost($lRes["date"], false).'</td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_news']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_news']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_news']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_news']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<a href=index.php?mod=news&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
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
				
				if ($total > 1)	$content_mod.="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";
				
			}
			else $content_mod = "Новостей нет.";
}
else
{
	if ($action=="delete_img")
	{
        $Db->query="SELECT `img` FROM `mod_news` WHERE `id_news`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_news` SET `img`='' WHERE `id_news` = '".$id."'"; 
		$Db->query();

		$img = $_SERVER['DOCUMENT_ROOT'].$lRes[img];
		unlink($img);
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news&action=edit&id=".$id."'></head></html>");
	}
	
	if ($action=="list_cat")
	{
		// запросы для постраничной навигации
			$num = $config["news"]["event_page_in_admin"]; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_event) FROM mod_event"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_news)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			
			$Db->query="SELECT * FROM `mod_event` ORDER BY `id_event` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=news&action=pereschet_cat" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Акция</td><td width="80"></td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {

					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=news&action=edit_cat&id='.$lRes["id_event"].'">'.$lRes["name_event"].'</a></td>
								<td></td><td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_event']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_event']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_event']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_event']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<a href=index.php?mod=news&action=lis_catt&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
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
				
				if ($total > 1)	$content_mod.="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";
				
			}
			else $content_mod = "Акций нет.";
	}
	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;

			 $Db->query="DELETE FROM `mod_news` WHERE `id_news` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_news SET act = '".$val."' WHERE id_news ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news&action=list'></head></html>");
	}
	if ($action=="pereschet_cat")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;

			 $Db->query="DELETE FROM `mod_event` WHERE `id_event` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_event SET act = '".$val."' WHERE id_event ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news&action=list_cat'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_news` WHERE id_news='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			if ($lRes['main']==1) $chek_main = " checked";
			if (empty($lRes["date"])) $date = date("Y-m-d"); else $date = $lRes["date"];
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название новости:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="80"></td>
  			</tr>
			<tr>
			<td width="40%"><p>Дата выхода:</p></td>
    		<td width="60%">
			<input type="text" name="date" value="'.$date.'" id="inputDate"></td>
			</tr>
			<tr height="30">
    		<td width="40%"><p>Картинка (опционально):<br />
			<span class="small">формат JPG,GIF или PNG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($lRes['img'])) $content_mod.='<input name="image" type="file">'; else $content_mod.="<img src='".$lRes['img']."'  align=middle hspace=10 border=0><a title='Удалить картинку' href='#' OnClick='Delete_news_img(".$lRes['id_news'].")'><img src='images/act_no.jpg' border='0' border=0 /></a><input type='hidden' name='img_load' value='".$lRes['img']."'>";
			$content_mod.= '</td>
  			</tr>
			</table>
  			
			<p>Содержимое новости</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text'])).'</textarea> 
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="time" value="'.$time.'"><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br />
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			if (!isset($_POST['img_load'])) {
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/news/".$myrand.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=100, $thumb_height=75, $do_cut=true);								
				}
				else { $img_name_full = "";}
			}
			else {$img_name_full = $_POST['img_load'];}
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$text = mysql_escape_string($_POST["text"]);
			$id = $_POST["id"];
			$anchor = trans($name);
			if (@$_POST["act"]) $act = 1; else $act = 0;
			$datemas = array(); 
   			$datemas = explode("/", htmlspecialchars($_POST['date']));
			$date=$_POST['date'];
			$Db->query="INSERT INTO `mod_news` (`id_news`, `name`, `text`, `anchor`,`act`,`date`,`img`)
						VALUES ('".$id."','".$name."','".$text."','".$anchor."','".$act."','".$date."','".$img_name_full."')
						ON DUPLICATE KEY UPDATE
						`id_news`=VALUES(`id_news`),
						`name`=VALUES(`name`),
						`text`=VALUES(`text`),
						`anchor`=VALUES(`anchor`),
						`act`=VALUES(`act`),
						`date`=VALUES(`date`),`img`=VALUES(`img`)";
						
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news&action=list'></head></html>");
			
		}
	}
	if ($action=="edit_cat")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_event` WHERE id_event='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";

			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			if ($id!="new") 
			{
				$date1 = date("d.m.Y", strtotime($lRes["start"])); 
				$date2 = date("d.m.Y", strtotime($lRes["finish"]));
			}
			else
			{
				$date1 = ""; 
				$date2 = "";
			}
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название акции:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_event'])).'" size="80"> 						</td>
  			</tr>
			</table>
			<p>Содержимое акции</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text'])).'</textarea><br />
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="time" value="'.$time.'">
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br /><br />
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$id = $_POST["id"];
			if (@$_POST["act"]) $act = 1; else $act = 0;
			$text = mysql_escape_string($_POST["text"]);
			$datemas = array(); 
   			$datemas = explode(".", htmlspecialchars($_POST['date']));
			$datemas2 = explode(".", htmlspecialchars($_POST['date2']));
			$date1=$datemas[2]."-".$datemas[1]."-".$datemas[0]." 00:00:00";
			$date2=$datemas2[2]."-".$datemas2[1]."-".$datemas2[0]." 00:00:00";
			$Db->query="INSERT INTO `mod_event` (`id_event`, `name_event`,`act`,`start`,`finish`,`text`)
						VALUES ('".$id."','".$name."','".$act."','".$date1."','".$date2."','".$text."')
						ON DUPLICATE KEY UPDATE
						`id_event`=VALUES(`id_event`),
						`name_event`=VALUES(`name_event`),
						`act`=VALUES(`act`),
						`start`=VALUES(`start`),`finish`=VALUES(`finish`),`text`=VALUES(`text`)";
						
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news&action=list_cat'></head></html>");
			
		}
	}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_news` WHERE `id_news` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=news'></head></html>");
	}	
}
if ($action=="config")
	{
		if (!@$_POST["save"])
		{
			$content_mod = '<h4>Настройки модуля:</h4>';
			$Db->query="SELECT * FROM `configutarion` WHERE `mod`='".$mod."'";
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
			$Db->query="UPDATE `configutarion` 
			SET `value` = CASE ".$query."
			ELSE `value` END";
			$Db->query(); 
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=".$mod."&action=config'></head></html>");
		}
	}

echo $content_mod;

// необходимые функции для этого модуля
?>