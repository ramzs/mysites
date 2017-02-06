<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_slider_img","config","pereschet","stat");
			parse_str($_SERVER['QUERY_STRING']);
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля
		
			//$content_mod = '<form method="post" class="filter_form">
			//Название <input type="text" name="name_filter" size="50" /><input type="submit" value="Поехали" name="submit_filter" /></form>';

			if (isset($_POST["submit_filter"])) 
			{
				$query_filter = "";
				$filter = new filter;
				$name_filter = $filter->html_filter($_POST["name_filter"]);
				if (!empty($name_filter)) $query_filter.= " AND `name` LIKE ('%".$name_filter."%')";
			}
			else $query_filter = "";
		
			// запросы для постраничной навигации
			$num = $config["actions"]["actions_page_in_admin"]; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_news) FROM mod_actions WHERE id_news!=0".$query_filter; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_news)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			
			$Db->query="SELECT *
			FROM `mod_actions` 
			 WHERE id_news!=0".$query_filter."
			ORDER BY mod_actions.date DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				
				$content_mod.= '<br /><form method="post" action="index.php?mod=actions&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td>Дата</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
			
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Показывается' title='Показывается' border=0 />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не показывается' title='Не показывается' border=0 />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=actions&action=edit&id='.$lRes["id_news"].$pagestr.'">'.$lRes["name"].'</a></td>
								
								<td width="120">'.formatedpost($lRes["date"]).'</td>
								
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
					if ($page!=$i) $navi.='<a href=index.php?mod=actions&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
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
			else
			{
				$content_mod = "Спецпредложений нет нет.";
			}
}
else
{
	if ($action=="stat")
	{
		$Db->query="SELECT `view`,`name`,`name_user`,`anchor`,mod_actions.date
					FROM  `mod_actions` 
					LEFT JOIN `mod_users_admin` ON (mod_users_admin.id_user=mod_actions.edit_id)
					WHERE `id_news`!='0' AND mod_actions.date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
					ORDER BY mod_actions.view DESC LIMIT 20";
		$Db->query();
		
		if (mysql_num_rows($Db->lQueryResult)>0) {
			$content_mod.= '<h4>20 популярных по просмотрам за 30 последних дней</h4><br /><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr class="head"><td>Просмотры</td><td>Название</td><td>Редактор</td><td>Дата</td></tr>';
			while($lRes=mysql_fetch_assoc($Db->lQueryResult))
			{
				 $content_mod.= '
				  <tr height="20" class="one_news">
					<td>'.$lRes["view"].'</td>
					<td><a href="/news/'.$lRes["anchor"].'.html" target="_blank">'.$lRes["name"].'</a></td>
					<td>'.$lRes["name_user"].'</td>
					<td>'.formatedpost($lRes["date"]).'</td>
				  </tr>';
			}
			$content_mod.= '</table>';
		}
		$Db->query="SELECT  COUNT(id) as allsum,mod_actions.name,mod_users_admin.name_user,mod_actions.anchor,mod_actions.date
					FROM  `mod_actions` 
					LEFT JOIN `mod_users_admin` ON (mod_users_admin.id_user=mod_actions.edit_id)
					LEFT JOIN `mod_comment` ON (mod_comment.rel_page=mod_actions.id_news)
					WHERE `id_news`!='0' AND mod_comment.mod='news' AND mod_actions.date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
 GROUP BY id_news
					ORDER BY allsum DESC LIMIT 10";
		$Db->query();
		
		if (mysql_num_rows($Db->lQueryResult)>0) {
			$content_mod.= '<h4>10 популярных по комментариям за 30 последних дней</h4>
			<br /><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr class="head"><td>Кол-во</td><td>Название</td><td>Редактор</td><td>Дата</td></tr>';
			while($lRes=mysql_fetch_assoc($Db->lQueryResult))
			{
				 $content_mod.= '
				  <tr height="20" class="one_news">
					<td>'.$lRes["allsum"].'</td>
					<td><a href="/news/'.$lRes["anchor"].'.html" target="_blank">'.$lRes["name"].'</a></td>
					<td>'.$lRes["name_user"].'</td>
					<td>'.formatedpost($lRes["date"]).'</td>
				  </tr>';
			}
			$content_mod.= '</table>';
		}
	}
	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `mod_actions` WHERE `id_news` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_actions SET act = '".$val."' WHERE id_news ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=actions&action=list'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_actions` WHERE id_news='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			
			$img=$lRes['slider_img'];
			$myparent = $lRes['cat'];
			$text = $lRes['full_text'];
			$slider_text = $lRes['slider_text'];
			if (empty($lRes["date"]) or $lRes["date"]=="0000-00-00 00:00:00") { $date = date("Y-m-d")." ".date("H:i:s");} else { $date = date("Y-m-d", strtotime($lRes["date"])); $time=date("H:i:s", strtotime($lRes["date"])); }
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			if ($id!="new") $content_mod.= "<div class='filter'><a href='/news/".$lRes['anchor'].".html' class='red' target='_blank'>Посмотреть на сайте</a></div>";
			
			/*
			<tr height="30">
    		<td width="40%"><p>Анонс новости (опционально):</p></td>
    		<td width="60%"><input type="text" name="anons" value="'.htmlspecialchars(stripslashes($lRes['anons'])).'" size="80"> 						</td>
  			</tr>
			*/
			
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="80"> 						</td>
  			</tr>
			
			<tr>
			<td width="40%"><p>Дата выхода:</p></td>
    		<td width="60%">
			<input type="text" name="date" value="'.$date.' '.$time.'" class="date_input" size="50">
			</td>
			</tr>
			
			<tr height="30">
    		<td width="40%"><p>Изображение в слайдере:<br />
			<span class="smallgray">формат JPG, оптимальный размер: 737*232 px</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($img)) $content_mod.='<input name="image" type="file">'; else $content_mod.="<img src='/upload/actions/sm".$img.".jpg' align=middle hspace=10><a title='Удалить картинку' href='#' OnClick='Delete_actions_slider_img(".$id.")'><img src='images/act_no.jpg' border='0' /></a><input type='hidden' name='img_load' value='".$img."'>";
			$content_mod.= '</td>
  			</tr>
			</table><br />
					
			<p>Текст в слайдере</p><br />
			<textarea name="slider_text" class="texta">'.htmlspecialchars(stripslashes($slider_text)).'</textarea> 			
			
			<input type="hidden" name="id" value="'.$id.'"><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br />
			<input type="hidden" name="time" value="'.$time.'">
			';
			
			$content_mod.= '<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			if (!isset($_POST['img_load'])) { //сохранение обложки товара
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/actions/sm".$myrand.".jpg";
					$img_name_full_large = "/upload/actions/".$myrand.".jpg";
					
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=73, $thumb_height=23, $do_cut=false);	
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full_large, $thumb_width=737, $thumb_height=232, $do_cut=true);	
					$img_name = $myrand;
				}
				else {$img_name = "";}
			}
			else {$img_name = $_POST['img_load'];}

			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$full_text = mysql_escape_string($_POST["full_text"]);
			$slider_text = mysql_escape_string($_POST["slider_text"]);
			$id = $_POST["id"];
			if (empty($_POST["anchor"])) $anchor = trans($_POST["name"]); 
			else $anchor = $filter->html_filter($_POST["anchor"]);
			
			if (@$_POST["act"]) $act = 1; else $act = 0;
			
			$date = $_POST['date'];
			$Db->query="INSERT INTO `mod_actions` (`id_news`, `name`, `full_text`, `anchor`,`act`,`date`,`slider_img`,`slider_text`)
						VALUES ('".$id."','".$name."','".$full_text."','".$anchor."','".$act."','".$date."','".$img_name."','".$slider_text."')
						ON DUPLICATE KEY UPDATE
						`id_news`=VALUES(`id_news`),
						`name`=VALUES(`name`),
						`full_text`=VALUES(`full_text`),
						`anchor`=VALUES(`anchor`),
						`slider_text`=VALUES(`slider_text`),
						`act`=VALUES(`act`), 
						
						`date`=VALUES(`date`),
						`slider_img`=VALUES(`slider_img`)";
			$Db->query();

			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=actions&action=list'></head></html>");
			
		}
	}
	if ($action=="delete_dop")
	{
        $Db->query="DELETE FROM `mod_dop` WHERE `id_dop` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=actions&action=edit&id=".$news."#tut'></head></html>");
	}

	if ($action=="delete_slider_img")
	{
        $Db->query="SELECT `slider_img` FROM `mod_actions` WHERE `id_news`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_actions` SET `slider_img`='' WHERE `id_news` = '".$id."'"; 
		$Db->query();

		unlink($_SERVER['DOCUMENT_ROOT']."/upload/actions/sm".$lRes[slider_img].".jpg");
		unlink($_SERVER['DOCUMENT_ROOT']."/upload/actions/".$lRes[slider_img].".jpg");
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=actions&action=edit&id=".$id."'></head></html>");
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