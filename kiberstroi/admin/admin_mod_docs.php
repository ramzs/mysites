<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_img","pereschet");
			parse_str($_SERVER['QUERY_STRING']);
			
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = 10; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_docs) FROM mod_docs"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_docs)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			
			$Db->query="SELECT * FROM `mod_docs` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=docs&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td width="130">Дата выхода</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {

					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=docs&action=edit&id='.$lRes["id_docs"].'">'.$lRes["name"].'</a></td>
								<td>'.formatedpost($lRes["date"], false).'</td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_docs']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_docs']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_docs']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_docs']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<a href=index.php?mod=docs&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
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
			else $content_mod = "Статей нет.";
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

			 $Db->query="DELETE FROM `mod_docs` WHERE `id_docs` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_docs SET act = '".$val."' WHERE id_docs ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=docs&action=list'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_docs` WHERE id_docs='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$myparent = $lRes['cat_docs'];
			
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="80"> 						</td>
  			</tr>
			<tr height="30">
    		<td><p>Заголовок страницы (title):<br />
			<span class="smallgray">отображается в верхней статусной строке браузера</span></p> </td>
    		<td>&nbsp;<input type="text" name="title" value="'.htmlspecialchars(stripslashes($lRes['title'])).'" size="80"></td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ключевые слова (keys):<br />
			<span class="smallgray">через запятую, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="keys" value="'.htmlspecialchars(stripslashes($lRes['keys'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Краткое описание (description):<br />
			<span class="smallgray">для поисковиков, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="meta" value="'.htmlspecialchars(stripslashes($lRes['meta'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Иконка в заголовке:<br />
			<span class="smallgray">из набора <a href="http://fontawesome.io/icons/" target="_blank" style="color: #ccc">Font Awesome</a></span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="icon" value="'.htmlspecialchars(stripslashes($lRes['icon'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Картинка (опционально):<br />
			<span class="small">формат JPG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($lRes['img'])) $content_mod.='<input name="image" type="file" accept="image/jpg">'; else $content_mod.="<img src='".$lRes['img']."' width='100' align=middle hspace=10 border=0><a title='Удалить картинку' href='#' OnClick='Delete_docs_img(".$lRes['id_docs'].")'><img src='images/act_no.jpg' border='0' border=0 /></a><input type='hidden' name='img_load' value='".$lRes['img']."'>";
			$content_mod.= '</td>
  			</tr>
			</table>
			<p>Анонс</p><br />
			<textarea name="anons" class="texta">'.htmlspecialchars(stripslashes($lRes['anons'])).'</textarea><br />
			<p>Содержимое</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text'])).'</textarea><br />';
			
			$content_mod.= '<p><br />Категория <select name="cat_docs">
			<option value="0">Нет категории</option>';
			$Db->query="SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent` FROM `mod_catalog_cat` WHERE `id_cat`!='0' ORDER BY `parent`,`rank`";
			$Db->query();
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select></p>';
			
			$content_mod .= '
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность
			<input type="hidden" name="id" value="'.$id.'">
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			if (!isset($_POST['img_load'])) {
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/docs/".$myrand.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=50, $thumb_height=50, $do_cut=true);
				}
				else { $img_name_full = ""; $img_name_full_large = "";}
			}
			else {$img_name_full = $_POST['img_load'];}
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$icon = $filter->html_filter($_POST["icon"]);
			$title = $filter->html_filter($_POST["title"]);
			$keys = $filter->html_filter($_POST["keys"]);
			$meta = $filter->html_filter($_POST["meta"]);
			$cat_docs = $filter->html_filter($_POST["cat_docs"]);
			$text = mysql_escape_string($_POST["text"]);
			$anons = mysql_escape_string($_POST["anons"]);
			$id = $_POST["id"];
		
			$anchor = trans(stripslashes($_POST['name']));
			if (@$_POST["act"]) $act = 1; else $act = 0;
			$datemas = array(); 
   			$datemas = explode("/", htmlspecialchars($_POST['date']));
			$date=$datemas[2]."-".$datemas[0]."-".$datemas[1]." ".$_POST['time'];
			$Db->query="INSERT INTO `mod_docs` (`id_docs`, `name`, `text`, `anchor`, `act`,`img`,`date`,`cat_docs`,`title`,`keys`,`meta`,`icon`,`anons`)
						VALUES ('".$id."','".$name."','".$text."','".$anchor."','".$act."','".$img_name_full."','".$date."','$cat_docs','".$title."','".$keys."','".$meta."','".$icon."','$anons')
						ON DUPLICATE KEY UPDATE
						`id_docs`=VALUES(`id_docs`),
						`name`=VALUES(`name`),
						`icon`=VALUES(`icon`),
						`text`=VALUES(`text`),
						`anchor`=VALUES(`anchor`),
						`act`=VALUES(`act`),
						`anons`=VALUES(`anons`),
						`img`=VALUES(`img`),
						`date`=VALUES(`date`),`cat_docs`=VALUES(`cat_docs`),`title`=VALUES(`title`),`keys`=VALUES(`keys`),`meta`=VALUES(`meta`)";
						
		if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=docs&action=list'></head></html>");
			
		}
	}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_docs` WHERE `id_docs` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=docs'></head></html>");
	}
	if ($action=="delete_img")
	{
        $Db->query="SELECT `img` FROM `mod_docs` WHERE `id_docs`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_docs` SET `img`='' WHERE `id_docs` = '".$id."'"; 
		$Db->query();

		$img = $_SERVER['DOCUMENT_ROOT'].$lRes[img];
		unlink($img);
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=docs&action=edit&id=".$id."'></head></html>");
	}
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

echo $content_mod;
// необходимые функции для этого модуля

	function forech_pages_select($data, $parent, $sub, $tire)
	{
		foreach($data as $k=>$v)
		{
			if (is_array($parent))
			{
				if(in_array($v['id_cat'],$parent)) 
					$chek = " selected='selected'"; 
				else 
					$chek = "";
			}
			else
			{
				if($v['id_cat']==$parent) 
					$chek = " selected='selected'"; 
				else 
					$chek = "";
			}
			$sub.='<option value="'.$v["id_cat"].'"'.$chek.'>'.$tire.$v['name_cat'].'</option>';
			
			if(isset($v['childs'])) 
				$sub .= forech_pages_select($v['childs'],$parent,"", $tire."----");
		}
		return $sub;
	}
	
	function getTreeMod(&$data, $parent)
	{
		$out=array();
		if(!isset($data[$parent]))
			return $out;
		foreach ($data[$parent] as $row)
		{
			$chidls = getTreeMod($data, $row['id_cat']);

			if ($chidls)
				$row['childs'] = $chidls;
			$out[]=$row;
		}
		return $out;
	}
?>