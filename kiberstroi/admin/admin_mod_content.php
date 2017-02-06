<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","down","up","config","pereschet");
			parse_str($_SERVER['QUERY_STRING']);
			
if (!in_array($action, $edit_array) or $action=="list") { //главная страница редактирования модуля
	
			$Db->query="SELECT * FROM `mod_content` LEFT JOIN `mod_users_admin` ON (mod_users_admin.id_user=mod_content.edit_id) ORDER BY `parent`,`rank`";
			$Db->query();
			
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			
			$content_mod.= forech_pages($data, 0, '');
			$content_mod.= '</table>';
}
else
{
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_content` WHERE id_content='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			if ($lRes['in_menu']==1) $chek_menu = " checked";
			$myparent = $lRes['parent'];
			if ($lRes['cat']==0) $chek_menu_top = ' checked="checked"'; else $chek_menu_bottom = ' checked="checked"';
			
			$content_mod.= "<div class='clear'></div>";
			$content_mod.= '<form method="post"> 
			<table border="0" cellspacing="0" cellpadding="0">';
			if ($id!="new") $content_mod.= '<tr height="30">
    		<td><p>Адрес страницы:</p> </td>
    		<td>'.$DomenName.'content/'.htmlspecialchars(stripslashes($lRes['anchor'])).'.html</td>
  			</tr>';
  			$content_mod.= '<tr height="30">
    		<td width="40%"><p>Название страницы в меню:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="80"> </td>
  			</tr>
  			<tr height="30">
    		<td><p>Заголовок страницы:<br />
			<span class="smallgray">отображается в верхней статусной строке браузера</span></p> </td>
    		<td>&nbsp;<input type="text" name="title" value="'.htmlspecialchars(stripslashes($lRes['title'])).'" size="80"></td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ключевые слова:<br />
			<span class="smallgray">через запятую, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="keys" value="'.htmlspecialchars(stripslashes($lRes['keys'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Краткое описание:<br />
			<span class="smallgray">для поисковиков, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="meta" value="'.htmlspecialchars(stripslashes($lRes['meta'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Редирект (опционально):</p></td>
    		<td width="60%">&nbsp;<input type="text" name="redirect" value="'.htmlspecialchars(stripslashes($lRes['redirect'])).'" size="80"> </td>
  			</tr>
			</table>
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Страница активна<br /><br />
			<input class="check" name="in_menu" type="checkbox"'.$chek_menu.' value="on" /> Показывать в меню<br /><br />
			
<!--			<br /><label><input type="radio" name="incat" value="0"'.$chek_menu_top.' /> Верхнее меню </label>
			<label><input type="radio" name="incat" value="1"'.$chek_menu_bottom.' /> Нижнее меню </label><br /><br />-->
			
			
			<p>Содержимое страницы</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text'])).'</textarea> 
			<input type="hidden" name="id" value="'.$id.'"><br />
			<input type="hidden" name="rank" value="'.$lRes['rank'].'">
			<input type="hidden" name="oldcat" value="'.$lRes['parent'].'"><br />
			<p>Родительская страница <select name="cat">
			<option value="0">Корень сайта</option>';
			$Db->query="SELECT `id_content`,`anchor`,`name`,`parent` FROM `mod_content` WHERE `id_content`!='".$id."' ORDER BY `parent`,`rank`";
			$Db->query();
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select></p>
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$title = $filter->html_filter($_POST["title"]);
			$keys = $filter->html_filter($_POST["keys"]);
			$meta = $filter->html_filter($_POST["meta"]);
			$text = mysql_escape_string($_POST["text"]);
			$redirect = $_POST["redirect"];
			$id = $_POST["id"];
			if ($id!=1) $anchor = trans($name); else $anchor = "index";
			if (@$_POST["act"]) $act = 1; else $act = 0;
			if (@$_POST["in_menu"]) $in_menu = 1; else $in_menu = 0;
			if (empty($_POST["rank"]) or $_POST["cat"]!=$_POST["oldcat"]) {
				$Db->query="SELECT COUNT(id_content) FROM `mod_content` WHERE `parent`='".$_POST["cat"]."'";
				$Db->query();
				$lRes=mysql_fetch_assoc($Db->lQueryResult);
				$rank = $lRes['COUNT(id_content)']+1;
				}
				else { $rank=$_POST["rank"]; }
			$Db->query="INSERT INTO `mod_content` (`id_content`, `name`, `title`, `keys`, `meta`, `text`, `anchor`, `act`, `in_menu`,`parent`,`rank`,`cat`,`redirect`,`edit_id`,`edit_date`)
						VALUES ('".$id."','".$name."','".$title."','".$keys."','".$meta."','".$text."','".$anchor."','".$act."','".$in_menu."','".$_POST['cat']."','".$rank."','".$_POST["incat"]."','".$redirect."','".$_SESSION['id_user']."',NOW())
						ON DUPLICATE KEY UPDATE
						`id_content`=VALUES(`id_content`),
						`name`=VALUES(`name`),
						`title`=VALUES(`title`),
						`keys`=VALUES(`keys`),
						`meta`=VALUES(`meta`),
						`text`=VALUES(`text`),
						`anchor`=VALUES(`anchor`),
						`act`=VALUES(`act`),
						`in_menu`=VALUES(`in_menu`),
						`parent`=VALUES(`parent`),
						`rank`=VALUES(`rank`),`cat`=VALUES(`cat`),
						`redirect`=VALUES(`redirect`),
						`edit_id`=VALUES(`edit_id`),
						`edit_date`=VALUES(`edit_date`)
						";
						
			$Db->query(); 

			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content&action=list'></head></html>");
		}
	}
	if ($action=="delete")
	{
		$Db->query="SELECT `rank`,`parent` FROM `mod_content` WHERE `id_content` = '".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$count = $lRes["rank"];
		$cat = $lRes["parent"];
        $Db->query="DELETE FROM `mod_content` WHERE `id_content` = '".$id."'"; 
		$Db->query();
		$Db->query="SELECT id_content,rank FROM mod_content WHERE parent='".$cat."'";
		$Db->query();
		if (mysql_num_rows($Db->lQueryResult)>0) {
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) { 
				if ($lRes["rank"] > $count) {
				$lRes["rank"] --;
				mysql_query("UPDATE `mod_content` SET `rank` = '".$lRes['rank']."' WHERE `id_content` = '".$lRes['id_content']."'");
				}
			}
		}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content'></head></html>");
	}
	if ($action=="up")
	{
		$up = $rank - 1;
		$Db->query="UPDATE mod_content SET rank = '$up' WHERE id_content = '".$id."'";
		$Db->query();
		$Db->query="SELECT id_content,rank FROM mod_content WHERE id_content!='".$id."' AND parent='".$cat."'";
		$Db->query();
		$query = "(" ;
		while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $queryrank[$lRes['id_content']] = $lRes['rank'];
			foreach ($queryrank as $key => $val) {
			if ($up==$val) { $val++; mysql_query("UPDATE mod_content SET rank = '".$val."' WHERE id_content='".$key."'");
				}
			}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content&action=list'></head></html>");
	}
	if ($action=="down")
	{
		$down = $rank + 1;
		$Db->query="UPDATE mod_content SET rank = '$down' WHERE id_content = '".$id."'";
		$Db->query();
		$Db->query="SELECT id_content,rank FROM mod_content WHERE id_content!='".$id."' AND parent='".$cat."'";
		$Db->query();
		$query = "(" ;
		while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $queryrank[$lRes['id_content']] = $lRes['rank'];
			foreach ($queryrank as $key => $val) {
			if ($down==$val) { $val--; mysql_query("UPDATE mod_content SET rank = '".$val."' WHERE id_content='".$key."'");
				}
			}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content&action=list'></head></html>");
	}
	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `mod_content` WHERE `id_content` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_content SET act = '".$val."' WHERE id_content ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=content&action=list'></head></html>");
	}
	if ($action=="config")
	{
		if (!@$_POST["save"])
		{
			$Db->query="SELECT * FROM `mod_config` WHERE `mod`='".$mod."'";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
			$content_mod.= "<br /><form action='index.php?mod=".$mod."&action=config' method='post'>";
				while($lRes=mysql_fetch_assoc($Db->lQueryResult))
				{				
				if($lRes[type]=="checkbox") $content_mod.= '
				<input type="hidden" name="'.$lRes[option].'" value="0">';
				if($lRes[type]=="checkbox" && $lRes["value"]=="on") $chek = ' checked="checked"'; else $chek = '';
				$content_mod.= '
				<p><input class="check" name="'.$lRes[option].'" type="'.$lRes[type].'"'.$chek.' value="'.$lRes[value].'" />&nbsp;&nbsp;&nbsp;'.$lRes[name].'</p>';
					
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

function checkbox_verify($_name)
{
$result=0;// обязательно прописываем, чтобы функция всегда возвращала результат 
// проверяем, а есть ли вообще такой checkbox на html форме, а то часто промахиваются
if (isset($_REQUEST[$_name]))
{ if ($_REQUEST[$_name]=='on') { $result=1; } else { $result=0; }
}
return $result;
}
function getTreeMod(&$data, $parent)
{
	$out=array();
	if (!isset($data[$parent]))
		return $out;
	foreach ($data[$parent] as $row)
	{
		$chidls=getTree($data, $row['id_content']);
		if ($chidls)
			$row['childs']=$chidls;
		$out[]=$row;
	}
	return $out;
}
function forech_pages($data, $temp, $slash)
{ 
$rank = sizeof($data, $num);
if ($temp==0) { 
	echo '<form method="post" action="index.php?mod=content&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td>Положение</td><td>Последняя редакция</td><td width="130" class="nobg"><div class="conf"><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
}
//echo '<ul>';
foreach ($data as $k=>$v)
    {
		if ($v['rank']!=$rank) $down = "<a href='index.php?mod=content&action=down&id=".$v['id_content']."&cat=".$v['parent']."&rank=".$v['rank']."' title='Двигать вниз'><img src='img/icons/down_arrow_no.png' class='pnghack' align='middle' border='0' /></a>"; else $down = "<img src='img/icons/down_arrow.png' align='middle' class='pnghack' />";
		if ($v['rank']!=1) $up = "<a href='index.php?mod=content&action=up&id=".$v['id_content']."&cat=".$v['parent']."&rank=".$v['rank']."' align='middle' title='Двигать вверх'><img src='img/icons/up_arrow_on.png' class='pnghack' border='0' /></a>"; else $up = "<img src='img/icons/up_arrow.png' align='middle' class='pnghack' />";
		//if ($v['in_menu']!=0) $in_menu = "<img src='images/actyes.png' class='pnghack' alt='Показывается в меню' title='Показывается в меню' />"; else $in_menu = "<img src='images/actno.png' class='pnghack' alt='Непоказывается в меню' title='Не показывается в меню' />";
       // if ($v['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Страница активна' title='Страница активна' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Страница не активна' title='Страница не активна' />";
		
		echo '<tr><td class="pages">'.$slash.'<a href="index.php?mod=content&action=edit&id='.$v["id_content"].'">'.$v['name'].'</a></td><td class="pages"';
		echo "</td><td class='pages'>".formatedpost($v['edit_date'])."&nbsp;&nbsp;/&nbsp;&nbsp;<b>".$v['name_user']."</b></td>
		<td class='pages'><div class='conf'>
		$up$down";
		echo "<input type='hidden' value='0' name='act[".$v['id_content']."]' />";
		if ($v['act']!=0) echo "<input type='checkbox' value='1' name='act[".$v['id_content']."]' class='checkboxact' checked='checked' />"; else echo "<input type='checkbox' value='1' name='act[".$v['id_content']."]' class='checkboxact' />";
		echo "<input type='checkbox' value='1' name='delete[".$v['id_content']."]' class='checkbox'  />
		</div>";
        if (isset($v['childs'])) forech_pages($v['childs'], 1, $slash."&nbsp;&nbsp;&nbsp;");
		echo "
		</td></tr>";
    }
//echo "</ul>";
if ($temp==0) 
	{
	echo '<tr><td></td><td><td></td></td><td>
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
}
function forech_pages_select($data,$parent,$sub,$tire)
{ 
foreach ($data as $k=>$v)
    {
		if ($v['id_content']==$parent) $chek = " selected='selected'"; else $chek = "";
        $sub.='<option value="'.$v["id_content"].'"'.$chek.'>'.$tire.$v['name'].'</option>';
        if (isset($v['childs'])) $sub=forech_pages_select($v['childs'],$parent,$sub, $tire."----");
    }
	return $sub;
}
?>