<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_img","down","up","config","delete_work_cat","delete_cat_all");
			parse_str($_SERVER['QUERY_STRING']);
			
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			
			echo '<div id="panel_stat">
			<form action="index.php?mod=catalog" method="post"><br />
			Название товара <input name="name_filter" type="text" value="" size="50" /> Код <input name="code_filter" type="text" value="" size="10" /> (<a href="code.php" target="_blank" class="red">дубли кодов</a>)
			<br /><input name="emptcode" type="checkbox" value="on" /> Пустое поле Код
			<br /><input name="emptprice" type="checkbox" value="on" /> Пустое поле Цена
			<br /><input name="inlider" type="checkbox" value="on" /> Лидеры продаж
			<br /><input name="inscroll" type="checkbox" value="on" /> Товары в карусели<br />
			<input class="but" type="submit" name="submit_filter" value="Поехали"/></form></div>';
			
			
			
			echo '<div class="incat"><div class="pages"><a href="#" onclick="newContent(\'good_list.php?cat_id=1\',\'goods\')">Неотсортированное (служебная категория)</a> <a href="#" onclick="Delete_work_cat(1)" class="red">(очистить категорию)</a></div>';
			
			$Db->query="SELECT `id_cat`,`name_cat`,`act`,`parent` FROM `mod_catalog_cat` WHERE `id_cat`!='1' ORDER BY parent,name_cat";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			$content_mod.= forech_pages($data);
			$content_mod.= "</div><div id='goods'></div>";
			}
			else
			$content_mod = "<br />Категорий нет";
}
else
{
	if ($action=="up")
	{
		$up = $rank - 1;
		$Db->query="UPDATE mod_catalog_cat SET rank = '$up' WHERE id_cat = '".$id."'";
		$Db->query();
		$Db->query="SELECT id_cat,rank FROM mod_catalog_cat WHERE id_cat!='".$id."' AND parent='".$cat."'";
		$Db->query();
		$query = "(" ;
		while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $queryrank[$lRes['id_cat']] = $lRes['rank'];
			foreach ($queryrank as $key => $val) {
			if ($up==$val) { $val++; $Db->query="UPDATE mod_catalog_cat SET rank = '".$val."' WHERE id_cat='".$key."'";
			$Db->query(); }
			}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
	}
	if ($action=="down")
	{
		$down = $rank + 1;
		$Db->query="UPDATE mod_catalog_cat SET rank = '$down' WHERE id_cat = '".$id."'";
		$Db->query();
		$Db->query="SELECT id_cat,rank FROM mod_catalog_cat WHERE id_cat!='".$id."' AND parent='".$cat."'";
		$Db->query();
		$query = "(" ;
		while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $queryrank[$lRes['id_cat']] = $lRes['rank'];
			foreach ($queryrank as $key => $val) {
			if ($down==$val) { $val--; $Db->query="UPDATE mod_catalog_cat SET rank = '".$val."' WHERE id_cat='".$key."'";
			$Db->query(); }
			}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_catalog_cat` WHERE `id_cat`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$myparent = $lRes['parent'];
			if ($lRes['act']==1) $chek = " checked";
			$content_mod = "
			<img src='images/left.jpg' alt='' align='middle' /> <a href='index.php?mod=mod_catalog_cat' class='red'>Вернуться назад</a><div class='clear'></div>
			<h3>Редактирование категории: <strong>".$lRes[name_cat]."</strong></h3><div class='clear'></div>";
			$content_mod.= '<form method="post" enctype="multipart/form-data"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название категории:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_cat'])).'" size="50"> </td>
  			</tr>
  			<tr height="30">
    		<td><p>Заголовок категории:<br />
			<spna class="small">отображается в верхней статусной строке браузера</span></p> </td>
    		<td>&nbsp;<input type="text" name="title" value="'.htmlspecialchars(stripslashes($lRes['title'])).'" size="50"></td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ключевые слова:<br />
			<spna class="small">через запятую, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="keys" value="'.htmlspecialchars(stripslashes($lRes['keys'])).'" size="50"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Краткое описание:<br />
			<spna class="small">для поисковиков, максимум 255 символов</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="meta" value="'.htmlspecialchars(stripslashes($lRes['meta'])).'" size="50"> </td>
  			</tr>
			</table>
			<input type="hidden" name="rank" value="'.$lRes['rank'].'">
			<input type="hidden" name="oldcat" value="'.$myparent.'"><br />
			<p>Текст категории:</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($lRes['text_cat'])).'</textarea> 
			<p>Родительская страница <select name="cat">
			<option value="0">Корень сайта</option>';
			$Db->query="SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent` FROM `mod_catalog_cat` WHERE `id_cat`!='".$id."' AND `id_cat`!='1' ORDER BY `parent`,`rank`";
			$Db->query();
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select></p>
			<input type="hidden" name="id" value="'.$id.'"><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br />
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{

if (!isset($_POST['img_load'])) { //сохранение обложки товара
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/cat/".$myrand.".jpg";
					imgResize($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, 115, 115, 0xFFFFFF, 90);					
					$img_name_full = $myrand;
				}
				else {$img_name_full = "";}
			}
			else {$img_name_full = $_POST['img_load'];}
			

			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$title = $filter->html_filter($_POST["title"]);
			$keys = $filter->html_filter($_POST["keys"]);
			$meta = $filter->html_filter($_POST["meta"]);
			$text = mysql_escape_string($_POST["text"]);
			$id = $_POST["id"];
			$cat = $_POST["cat"];
			$anchor = trans($name);
			if (@$_POST["act"]) $act = 1; else $act = 0;
			if (empty($_POST["rank"]) or $_POST["cat"]!=$_POST["oldcat"]) {
				$Db->query="SELECT COUNT(id_cat) FROM `mod_catalog_cat` WHERE `parent`='".$_POST["cat"]."' AND id_cat!='1'";
				$Db->query();
				$lRes=mysql_fetch_assoc($Db->lQueryResult);
				$rank = $lRes['COUNT(id_cat)']+1;
				}
				else { $rank=$_POST["rank"]; }
			$Db->query="INSERT INTO `mod_catalog_cat` (`id_cat`, `name_cat`, `title`, `keys`, `meta`, `text_cat`, `act`,`parent`, `anchor_cat`,`img_cat`,`rank`)
						VALUES ('".$id."','".$name."','".$title."','".$keys."','".$meta."','".$text."','".$act."','".$cat."','".$anchor."','".$img_name_full."','".$rank."')
						ON DUPLICATE KEY UPDATE
						`id_cat`=VALUES(`id_cat`),
						`name_cat`=VALUES(`name_cat`),
						`title`=VALUES(`title`),
						`keys`=VALUES(`keys`),
						`meta`=VALUES(`meta`),
						`text_cat`=VALUES(`text_cat`),
						`act`=VALUES(`act`),
						`rank`=VALUES(`rank`),
						`parent`=VALUES(`parent`),
						`img_cat`=VALUES(`img_cat`),
						`anchor_cat`=VALUES(`anchor_cat`)";	
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
		}
	}
	if ($action=="delete_img")
	{
        $Db->query="SELECT `img_cat` FROM `mod_catalog_cat` WHERE `id_cat`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_catalog_cat` SET `img_cat`='' WHERE `id_cat` = '".$id."'"; 
		$Db->query();
		$img = $_SERVER['DOCUMENT_ROOT']."/upload/cat/sm".$lRes[img_cat].".jpg";
		unlink($img);
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat&action=edit&id=".$id."'></head></html>");
	}
	if ($action=="delete_work_cat")
	{
		$Db->query="DELETE FROM `mod_catalog` WHERE `cat` = '1'"; 
		$Db->query();
exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
	}
	if ($action=="delete_cat_all")
	{
		$Db->query="DELETE FROM `mod_catalog` WHERE `cat` = '".$id."'"; 
		$Db->query();
exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
	}
	if ($action=="delete")
	{
       $Db->query="SELECT `rank`,`parent` FROM `mod_catalog_cat` WHERE `id_cat` = '".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$count = $lRes["rank"];
		$cat = $lRes["parent"];
        $Db->query="DELETE FROM `mod_catalog_cat` WHERE `id_cat` = '".$id."'"; 
		$Db->query();
		$Db->query="DELETE FROM `mod_catalog_cat` WHERE `parent` = '".$id."'"; 
		$Db->query();
		$Db->query="DELETE FROM `mod_catalog` WHERE `cat` = '".$id."'"; 
		$Db->query();
		$Db->query="SELECT id_cat,rank FROM mod_catalog_cat WHERE parent='".$cat."' AND id_cat!='1'";
		$Db->query();
		if (mysql_num_rows($Db->lQueryResult)>0) {
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) { 
				if ($lRes["rank"] > $count) {
				$lRes["rank"] --;
				mysql_query("UPDATE `mod_catalog_cat` SET `rank` = '".$lRes['rank']."' WHERE `id_cat` = '".$lRes['id_cat']."'");
				}
			}
		}
		$Db->query="DELETE FROM `mod_catalog` WHERE `cat` = '".$id."'"; 
		$Db->query();
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=mod_catalog_cat'></head></html>");
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
				if (!@$_POST["save_import"])
				{
		$content_mod.= "<div style='width:650px;border:1px dashed #333;padding:10px; margin-right:20px'><h3>Импорт прайс-листа</h3><div style='clear:both'></div>
<b>Инструкция:</b> <br />
<br />
Для формирования необходимого файла для импорта необходимо использовать программу OpenOffice Calc. В списке товаров не должно быть лишних строк (названий категорий,заголовков и пр.). <br />
<br />
Столбцы должны быть в следующем порядке - <b>Название | Остаток | Код | Цена </b>(цена - только цифры). <br />
<br />
При сохранении файла указать формат <b>.csv</b>. <br />Разделитель поля - <b>:</b> Разделитель текста - <b>'</b> <br /><br />
<form action='' method='post' enctype='multipart/form-data'><input type='file' name='import'><br />
<br /><input name='act_off' type='checkbox' value='on' checked='checked' /> Снять активность со всех товаров перед обновлением<br />
<input type='submit' value='Отправить' name='save_import' class='but' /></form><br />
Внимание! Время выполнения скрипта зависит от кол-ва загружаемых товаров.
</div>";
				}
				else
				{
		if (!empty($_FILES["import"])) 
		{
			if (@$_POST["act_off"]) mysql_query("UPDATE `mod_catalog` SET `act`='0'");
			$Db->query="SELECT `code` FROM `mod_catalog`";
			$Db->query();
			while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $myarray[]=$lRes["code"];

			$lines = file($_FILES["import"]["tmp_name"]);
 			foreach ($lines as $line_num => $line) {
 				$data = $line;
 				list($name, $amount, $cod, $price) = explode(":", $data);
				$name=str_replace("'","",trim($name));
				$cod=str_replace("'","",$cod);
				if (in_array($cod, $myarray)) {
					if (empty($amount)) $est = "0"; else $est = "1"; 
					$update	= mysql_query("UPDATE `mod_catalog` SET `price`='".$price."',`act`='1',`avail`='".$est."',`name_goods`='".$name."',`title`='".$name." - Компания \"ВолгаКонтинент\" - купить в Саратове посуду, сувениры, картины, подарки', `meta`='".$name." - Компания \"ВолгаКонтинент\" - купить в Саратове посуду, сувениры, картины, подарки', `keys`='".$name.",купить в Саратове, посуда, сувениры, картины, подарки' WHERE `code`='".$cod."'");
					if ($update==false) exit(mysql_error());
				}
				else
				{
					if (empty($amount)) $est = "0"; else $est = "1"; 
					mysql_query("INSERT INTO `mod_catalog` (`name_goods`,`title`,`meta`,`keys`,`code`,`price`,`cat`,`act`,`avail`) VALUES ('".$name."','".$name." - Компания \"ВолгаКонтинент\" - купить в Саратове посуду, сувениры, картины, подарки','".$name." - Компания \"ВолгаКонтинент\" - купить в Саратове посуду, сувениры, картины, подарки','".$name.", купить в Саратове посуда, сувениры, картины, подарки','".$cod."','".$price."','1','1','".$est."')");
				}
 			}
		//	echo "<h3>Импорт прошел успешно.</h3><div style='clear:both'></div>";
		//	exit("<html><head><meta  http-equiv='Refresh' content='3; URL=index.php?mod=mod_catalog_cat'></head></html>");
		}					
				}

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

		echo '<li class="pages"><a href="#" onclick="newContent(\'good_list.php?cat_id='.$v['id_cat'].'\',\'goods\')">'.$v['name_cat'].'</a>';
		echo '<a href="index.php?mod=mod_catalog_cat&action=edit&id='.$v["id_cat"].'"><img src="images/edit.png" border="0" class="pnghack" align="absmiddle" hspace="5"  /></a>';
		echo "<a href='#' onclick='Delete_cat(".$v["id_cat"].")' title='Удалить категорию'><img src='images/del.png' class='pnghack' align='absmiddle' /></a>";
		echo "<a href='index.php?mod=catalog&action=edit&id=new&cat=".$v['id_cat']."' title='Добавить товар в эту категорию'><img src='images/plus_green.png'  class='pnghack' align='absmiddle' hspace='5' /></a> <a href='#' onclick='Delete_cat_all(".$v["id_cat"].")'>(очистить)</a>";
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