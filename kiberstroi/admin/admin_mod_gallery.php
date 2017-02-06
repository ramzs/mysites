<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","config","pereschet", "delete_gallery_cover", "delete_gallery_img");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			$content_mod = '<form method="post" class="filter_form">
			Название <input type="text" name="name_filter" size="50" /><input type="submit" value="Поехали" class="but" name="submit_filter"></form>';

			if (isset($_POST["submit_filter"])) 
			{
				$query_filter = "";
				$filter = new filter;
				$name_filter = $filter->html_filter($_POST["name_filter"]);
				if (!empty($name_filter)) $query_filter.= " AND `name_gallery` LIKE ('%".$name_filter."%')";
			}
			else $query_filter = "";

			// запросы для постраничной навигации
			$num = 20;  // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_gallery) FROM mod_gallery WHERE `id_gallery`!='0'".$query_filter; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_gallery)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			
			$Db->query="SELECT * FROM `mod_gallery` WHERE `id_gallery`!='0'".$query_filter." ORDER BY mod_gallery.date DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=gallery&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td>Дата выхода</td><td width="130" class="nobg"><div class="conf"><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {

					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=gallery&action=edit&id='.$lRes["id_gallery"].$pagestr.'">'.$lRes["name_gallery"].'</a></td>
								<td>'.formatedpost($lRes["date"]).'</td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_gallery']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_gallery']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_gallery']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_gallery']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<a href=index.php?mod=gallery&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
				}
				
				$content_mod.= '<tr><td></td><td></td></td><td>
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
			else $content_mod = "Альбомов нет.";
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
			 
			 $Db->query="SELECT `source` FROM `mod_file_gallery` WHERE `album` IN ".$query;
			 $Db->query();
			 while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
			 {
				 unlink($_SERVER['DOCUMENT_ROOT']."/upload/gallery/".$lRes["source"].".jpg");
				 unlink($_SERVER['DOCUMENT_ROOT']."/upload/gallery/bg".$lRes["source"].".jpg");
			 }
			 
			 $Db->query="DELETE FROM `mod_gallery` WHERE `id_gallery` IN ".$query;
			 $Db->query();
			 $Db->query="DELETE FROM `mod_file_gallery` WHERE `album` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_gallery SET act = '".$val."' WHERE id_gallery ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=gallery&action=list'></head></html>");
	}
	
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_gallery` WHERE `id_gallery`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			$text_gallery = $lRes['text_gallery'];
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$myparent = $lRes['cat'];

			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=gallery&action=edit&id='.$id.'"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_gallery'])).'" size="80"> 						</td>
  			</tr>
			<tr height="30">
    		<td><p>Заголовок:<br />
			<spna class="smallgray">отображается в верхней статусной строке браузера</span></p> </td>
    		<td><input type="text" name="title" value="'.htmlspecialchars(stripslashes($lRes['title'])).'" size="80"></td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ключевые слова:<br />
			<spna class="smallgray">через запятую, максимум 255 символов</span></p></td>
    		<td width="60%"><input type="text" name="keys" value="'.htmlspecialchars(stripslashes($lRes['keys'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Краткое описание:<br />
			<spna class="smallgray">для поисковиков, максимум 255 символов</span></p></td>
    		<td width="60%"><input type="text" name="meta" value="'.htmlspecialchars(stripslashes($lRes['meta'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Обложка:<br />
			<span class="smallgray">формат JPG,GIF или PNG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($lRes['cover'])) $content_mod.='<input name="image" type="file" accept="image/jpeg">'; else $content_mod.="<img src='".$lRes['cover']."' width='100' align=middle hspace=10>
			
			<a class='delete_item' href='#' OnClick='Delete_gallery_cover(".$id.")' title='Удалить картинку'>
			<img src='images/act_no.jpg' border='0' /></a><input type='hidden' name='img_load' value='".$lRes['cover']."'>";
			
			$content_mod.= '</td>
  			</tr>
			</table>
			<p>Текстовое содержимое</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($text_gallery)).'</textarea><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="time" value="'.$time.'">';
			$content_mod.= '<br /><h4><strong>Файлы фотоальбома:</strong></h4>';
				$Db->query="SELECT * FROM `mod_file_gallery` WHERE `album`='".$id."'";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
						//$content_mod.= '<br />Фотографии, указанные под номерами 1,2,3,4 отображаются заглавными<br /><br />';
					while($lRes=mysql_fetch_assoc($Db->lQueryResult))
						{
						$content_mod.= '<div class="one_gallery"><img src="/upload/gallery/'.$lRes["source"].'.jpg" height="80" hspace="5" align="middle" />
						<a class="delete_item" href="#" OnClick="Delete_gallery_img('.$lRes['id_file'].', '.$lRes['album'].')" title="Удалить картинку">
						<img src="images/act_no.jpg" border="0" /></a><!--<input type="text" value="'.$lRes["rank_file"].'" name="rank_file['.$lRes["id_file"].']" size="5" />--></div>';
						}
					}
					else
					{
					$content_mod.= "Файлов нет";
					}
				$content_mod.= '<br /><br /><div class="clear"><br /><br /></div><div style="display: inline; padding: 2px; clear: both; margin-top: 10px;">
									<span id="spanButtonPlaceholder"></span>
								</div>
				<div id="divFileProgressContainer" style="height: 75px;"></div>
					<div id="thumbnails"></div>';
			
			$content_mod.= '<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			if (!isset($_POST['img_load'])) { // обложка альбома
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/gallery/cover/".$myrand.".jpg";
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=160, $thumb_height=118, $do_cut=true);
				}
				else { $img_name_full = ""; $img_name_full_large = "";}
			}
			else {$img_name_full = $_POST['img_load'];}

			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$title = $filter->html_filter($_POST["title"]);
			$meta = $filter->html_filter($_POST["meta"]);
			$keys = $filter->html_filter($_POST["keys"]);
			$text = mysql_escape_string($_POST["text"]);
			$id = $_POST["id"];
			$anchor = trans(htmlspecialchars($name));
			$anchor=str_replace("&quot;","",stripslashes($anchor));
			if (@$_POST["act"]) $act = 1; else $act = 0;
			$datemas = array(); 
   			$datemas = explode("/", htmlspecialchars($_POST['date']));
			$date=$datemas[2]."-".$datemas[0]."-".$datemas[1]." ".$_POST['time'];
			
			$Db->query="INSERT INTO `mod_gallery` (`id_gallery`, `name_gallery`, `text_gallery`, `act`,`cover`,`anchor`,`date`,`edit_id`,`edit_date`,`title`,`meta`,`keys`)
						VALUES ('".$id."','".$name."','".$text."','".$act."','".$img_name_full."','".$anchor."',NOW(),'".$_SESSION['id_user']."', NOW(),'".$title."','".$meta."','".$keys."')
						ON DUPLICATE KEY UPDATE
						`id_gallery`=VALUES(`id_gallery`),
						`name_gallery`=VALUES(`name_gallery`),
						`text_gallery`=VALUES(`text_gallery`),
						`act`=VALUES(`act`),
						`cover`=VALUES(`cover`),
						`edit_id`=VALUES(`edit_id`),
						`edit_date`=VALUES(`edit_date`),
						`anchor`=VALUES(`anchor`),
						`title`=VALUES(`title`),
						`meta`=VALUES(`meta`),
						`keys`=VALUES(`keys`)";
			$content_mod.= "<br /><br /><p align='center'><img src='/images/loader.gif' /></p>";
			if ($Db->query())
			{
				//print_r($_SESSION["file_need"]);
				if (!empty($_SESSION["file_need"])) //мультизагрузка файлов
						{
						if ($id=="new") $post_id = mysql_insert_id(); else $post_id=$id;
						$msg = "";
						foreach ($_SESSION["file_need"] as $key=>$value) $msg.= $value."|";	
						
						if (!empty($msg)) {
							$file = explode("|", substr($msg,0,-1));
							$file_large = explode("|", substr($msg_full,0,-1));
							$input = "";
							foreach ($file as $key=>$value) 
							{
								$input.= "('".$value."','".$post_id."'),";
								rename($_SERVER['DOCUMENT_ROOT']."/upload/temp/bg".$value.".jpg", $_SERVER['DOCUMENT_ROOT']."/upload/gallery/bg".$value.".jpg");
								rename($_SERVER['DOCUMENT_ROOT']."/upload/temp/".$value.".jpg", $_SERVER['DOCUMENT_ROOT']."/upload/gallery/".$value.".jpg");

							}
							$input = substr($input,0,-1);
							$Db->query="INSERT INTO `mod_file_gallery` (`source`,`album`) VALUES ".$input;
							$Db->query();
						}
						
				}
				unset($_SESSION["file_need"]);
				
				if (!empty($_POST["rank_file"]))
				{
					$query = '';
					foreach ($_POST["rank_file"] as $key=>$value) $query.= " WHEN `id_file`='".$key."' THEN '".$value."'";	
					$Db->query="UPDATE `mod_file_gallery` 
					SET `rank_file` = CASE ".$query."
					ELSE `rank_file` END";
					$Db->query(); 
				}

				exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=gallery&action=list'></head></html>");
			}
		}
	}
	if ($action=="delete_gallery_cover")
	{
        $Db->query="SELECT `cover` FROM `mod_gallery` WHERE `id_gallery`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_gallery` SET `cover`='' WHERE `id_gallery` = '".$id."'"; 
		$Db->query();

		if (!empty($lRes['cover'])) unlink($_SERVER['DOCUMENT_ROOT'].$lRes['cover']);

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=gallery&action=edit&id=".$id."'></head></html>");
	}
	if ($action=="delete_gallery_img")
	{
        $Db->query="SELECT `source` FROM `mod_file_gallery` WHERE `id_file`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="DELETE FROM `mod_file_gallery` WHERE `id_file` = '".$id."'"; 
		$Db->query();

		if (!empty($lRes['source'])) unlink($_SERVER['DOCUMENT_ROOT']."/upload/gallery/".$lRes['source'].".jpg");
		if (!empty($lRes['source'])) unlink($_SERVER['DOCUMENT_ROOT']."/upload/gallery/bg".$lRes['source'].".jpg");

		exit("<html><head><meta http-equiv='Refresh' content='0; URL=index.php?mod=gallery&action=edit&id=".$cat."'></head></html>");
	}
	if ($action=="config")
	{
		if (!@$_POST["save"])
		{
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
		if ($v['rank']!=$rank) $down = "<a href='index.php?mod=categories&action=down&id=".$v['id_cat']."&cat=".$v['parent']."&rank=".$v['rank']."' title='Двигать вниз'><img src='images/down.png' class='pnghack' /></a>"; else $down = "<img src='images/nodown.png' class='pnghack' />";
		if ($v['rank']!=1) $up = "<a href='index.php?mod=categories&action=up&id=".$v['id_cat']."&cat=".$v['parent']."&rank=".$v['rank']."' title='Двигать вверх'><img src='images/up.png' class='pnghack' /></a>"; else $up = "<img src='images/noup.png' class='pnghack' />";
        if ($v['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Страница активна' title='Страница активна' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Страница не активна' title='Страница не активна' />";
		
		echo '<li class="pages"><a href="index.php?mod=categories&action=edit&id='.$v["id_cat"].'">'.$v['name_cat'].'</a>';
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