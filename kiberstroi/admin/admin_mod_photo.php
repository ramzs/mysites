<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_img","delete_img_report","config");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = 10;  // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_photo) FROM mod_photo"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_photo)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			
			$Db->query="SELECT * FROM `mod_photo` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr class="head_news">
								<td>Название</td>
								<td width="50"></td>
								</tr>';
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Показывается' title='Показывается' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не показывается' title='Не показывается' />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=photo&action=edit&id='.$lRes["id_photo"].$pagestr.'">'.$lRes["name_photo"].'</a></td>
								<td>'.$act.'
								<a href="#" onclick="Delete_photo('.$lRes["id_photo"].')" title="Удалить альбом"><img src="images/del.png" class="pnghack" /></a>
								</td>
								</tr>';
								$num++;
				}
				// Проверяем нужны ли стрелки назад
				if ($page != 1) $pervpage = '<a href="index.php?mod=photo&page='. ($page - 1) .'"><< Предыдущая страница</a>';
				// Проверяем нужны ли стрелки вперед
				if ($page != $total) $nextpage = '<a href="index.php?mod=photo&page='. ($page + 1) .'">Следующая страница >></a>';
				if ($total > 1)	$navigation="<table class=\"pstrnav\"><tr><td class='last'>".$pervpage."</td><td class='all_page'>Вы находитесь на странице: $page, всего страниц: $total</td><td class='next'>".$nextpage."</td></tr></table>";
				$content_mod.= "</table>".$navigation;
			}
			else
			{
				$content_mod = "<br />Альбомов нет.";
			}
}
else
{
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_photo` WHERE `id_photo`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			$text_photo = $lRes['text_photo'];
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$myparent = $lRes['cat'];
			$content_mod = "
			<img src='images/left.jpg' alt='' align='middle' /> <a href='index.php?mod=photo".$pagestr."' class='red'>Вернуться назад</a><div class='clear'></div>
			<h3>Редактирование альбома: <strong>".$lRes[name_photo]."</strong></h3><div class='clear'></div>";
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=photo&action=edit&id='.$id.'"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_photo'])).'" size="50"> 						</td>
  			</tr>
			
			<tr height="30">
    		<td width="40%"><p>Обложка:<br />
			<span class="small">формат JPG,GIF или PNG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($lRes['cover'])) $content_mod.='<input name="image" type="file" accept="image/jpeg">'; else $content_mod.="<img src='".$lRes['cover']."' width='100' align=middle hspace=10><a title='Удалить картинку' href='#' OnClick='Delete_photo_img(".$lRes['id_photo'].")'><img src='images/act_no.jpg' border='0' /></a><input type='hidden' name='img_load' value='".$lRes['cover']."'>";
			
			$content_mod.= '</td>
  			</tr>
			</table>
			<br />
			<p>Описание</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($text_photo)).'</textarea><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="time" value="'.$time.'">';

			$content_mod.= '<br /><h4><strong>Файлы фотоальбома:</strong></h4>';
				$Db->query="SELECT `id_file`,`source` FROM `mod_file_photo` WHERE `album`='".$id."'";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
					while($lRes=mysql_fetch_assoc($Db->lQueryResult))
						{
						$content_mod.= '<img src="/upload/photo/'.$lRes["source"].'.jpg" height="80" hspace="5" align="middle" /><a title="Удалить картинку" href="#" OnClick="Delete_photo_report_img('.$lRes["id_file"].')"><img src="images/act_no.jpg" border="0" /></a>';
						}
					}
					else
					{
					$content_mod.= "Файлов нет";
					}
				$content_mod.= '<br /><br /><input type="file" class="multi" name="fileToUpload[]" />';
			
			$content_mod.= '<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			if (!isset($_POST['img_load'])) { // обложка альбома
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/photo/cover/".$myrand.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=300, $thumb_height=130, $do_cut=true);
				}
				else { $img_name_full = ""; $img_name_full_large = "";}
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
			$date=$datemas[2]."-".$datemas[0]."-".$datemas[1]." ".$_POST['time'];
			
			$Db->query="INSERT INTO `mod_photo` (`id_photo`, `name_photo`, `text_photo`, `act`,`cover`,`anchor`,`date`)
						VALUES ('".$id."','".$name."','".$text."','".$act."','".$img_name_full."','".$anchor."',NOW())
						ON DUPLICATE KEY UPDATE
						`id_photo`=VALUES(`id_photo`),
						`name_photo`=VALUES(`name_photo`),
						`text_photo`=VALUES(`text_photo`),
						`act`=VALUES(`act`),
						`cover`=VALUES(`cover`),
						`anchor`=VALUES(`anchor`)";
			$content_mod.= "<br /><br /><p align='center'><img src='/images/loader.gif' /></p>";
			$Db->query();
						  
			if (!empty($_FILES["fileToUpload"]["name"])) //мультизагрузка файлов
					{
					$fileElementName = 'fileToUpload';
					$i = 0;
					$msg = "";
					$msg_full = "";
					$files_count = sizeof($_FILES[$fileElementName]["name"]);
						for ($i = 0; $i < $files_count; $i++) {	
							if(empty($_FILES[$fileElementName]['tmp_name'][$i]) || $_FILES[$fileElementName]['tmp_name'][$i] == 'none')
							{	
							$msg = "";
							}
							else 
							{
								$myname = rand();
								$myname_full = "/upload/photo/".$myname.".jpg";
								$myname_full_large = "/upload/photo/large".$myname.".jpg";
								if (horizorvert($_FILES[$fileElementName]['tmp_name'][$i])!=1) { //если картинка горизонтальная
create_thumbnail($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full, $thumb_width=85, $thumb_height=113, $do_cut=true);
create_thumbnail($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full_large, $thumb_width=375, $thumb_height=500, $do_cut=true);
									}
									else
									{ //если картинка вертикальная
create_thumbnail($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full, $thumb_width=150, $thumb_height=113, $do_cut=true);
create_thumbnail($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full_large, $thumb_width=500, $thumb_height=375, $do_cut=true);
									}
								$msg.= $myname."|";
								
								@unlink($_FILES[$fileElementName][$i]);		
							}
				
						}
					}
					if (!empty($msg)) {
						
						if ($_POST["id"]=="new") $id = mysql_insert_id(); else $id = $_POST["id"];
						
						$file = explode("|", substr($msg,0,-1));
						$file_large = explode("|", substr($msg_full,0,-1));
						$input = "";
						foreach ($file as $key=>$value) $input.= "('".$value."','".$id."'),";
						$input = substr($input,0,-1);
						$Db->query="INSERT INTO `mod_file_photo` (`source`,`album`) VALUES ".$input;
						$Db->query();
					}			  
						  
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=photo'></head></html>");
			
		}
	}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_photo` WHERE `id_photo` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=photo'></head></html>");
	}
	if ($action=="delete_img")
	{
        $Db->query="SELECT `cover` FROM `mod_photo` WHERE `id_photo`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_photo` SET `cover`='' WHERE `id_photo` = '".$id."'"; 
		$Db->query();
		$img = $_SERVER['DOCUMENT_ROOT'].$lRes[cover];
		unlink($img);
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=photo&action=edit&id=".$id."'></head></html>");
	}
	if ($action=="delete_img_report")
	{
        $Db->query="SELECT `source`,`source_large`,`photo` FROM `mod_file_photo` WHERE `id_file`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="DELETE FROM `mod_file_photo` WHERE `id_file` = '".$id."'"; 
		$Db->query();
		$img = $_SERVER['DOCUMENT_ROOT'].$lRes[source];
		$img_large = $_SERVER['DOCUMENT_ROOT'].$lRes[source_large];
		unlink($img);
		unlink($img_large);
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=photo&action=edit&id=".$lRes[photo]."'></head></html>");
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
}


echo $content_mod;
// необходимые функции для этого модуля
function getTreeMod(&$data, $parent)
{
	$out=array();
	if (!isset($data[$parent]))
		return $out;
	foreach ($data[$parent] as $row)
	{
		$chidls=getTree($data, $row['id_cat']);
		if ($chidls)
			$row['childs']=$chidls;
		$out[]=$row;
	}
	return $out;
}
function forech_pages($data)
{ 
$rank = sizeof($data);
echo '<ul>';
foreach ($data as $k=>$v)
    {
		if ($v['rank']!=$rank) $down = "<a href='index.php?mod=mod_catalog_cat&action=down&id=".$v['id_cat']."&cat=".$v['parent']."&rank=".$v['rank']."' title='Двигать вниз'><img src='images/down.png' class='pnghack' /></a>"; else $down = "<img src='images/nodown.png' class='pnghack' />";
		if ($v['rank']!=1) $up = "<a href='index.php?mod=mod_catalog_cat&action=up&id=".$v['id_cat']."&cat=".$v['parent']."&rank=".$v['rank']."' title='Двигать вверх'><img src='images/up.png' class='pnghack' /></a>"; else $up = "<img src='images/noup.png' class='pnghack' />";
        if ($v['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Страница активна' title='Страница активна' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Страница не активна' title='Страница не активна' />";
		
		echo '<li class="pages"><a href="index.php?mod=mod_catalog_cat&action=edit&id='.$v["id_cat"].'">'.$v['name_cat'].'</a>';
		echo "<div class='conf'>
		$act$down$up
		<a href='#' onclick='Delete_cat(".$v["id_cat"].")' title='Удалить категорию'><img src='images/del.png' class='pnghack' /></a>
		</div>";
        if (isset($v['childs'])) forech_pages($v['childs']);
		echo "
		</li>";
    }
echo "</ul>";
}
function forech_pages_select($data,$parent,$sub,$tire)
{ 
foreach ($data as $k=>$v)
    {
		if ($v['id_cat']==$parent) $chek = " selected='selected'"; else $chek = "";
        $sub.='<option value="'.$v["id_cat"].'"'.$chek.'>'.$tire.$v['name_cat'].'</option>';
        if (isset($v['childs'])) $sub.=forech_pages_select($v['childs'],$parent,"", $tire."----");
    }
	return $sub;
}
?>