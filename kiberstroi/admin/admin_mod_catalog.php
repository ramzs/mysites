<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","config","edit_cat","list_cat","pereschet","pereschet_cat",  "delete_catalog_logo","delete_cat_img","delete_company_img","edit_mark","list_mark","import","delete_img","dubl","delete_good_cover", "delete_goods_img","edit_brand","list_brand","delete_brand_logo","pereschet_brand","list_online","edit_online","delete_online", "delete_dop", "list_filter", "edit_filter", "pereschet_filter", "edit_podfilter", "delete_filter", "add_filter_in_goods", "delete_filter_in_goods");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			$content_mod = '<form method="post" class="filter_form">
			Название <input type="text" name="name_filter" size="50" /> Код <input type="text" name="code_filter" size="10" /> Категория <select name="cat">
			<option value="0">Выберите категорию</option>';
			
				$Db->query = "SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent` FROM `mod_catalog_cat` WHERE `act`='1' ORDER BY `parent`,`rank`";
				$Db->query();
				while($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					$data[$lRes['parent']][] = $lRes;
					
				$data = getTreeMod($data, 0);
				$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select>
			<input type="submit" value="Поехали" class="but" name="submit_filter"></form>';

			if (isset($_POST["submit_filter"])) 
			{
				$query_filter = "";
				$filter = new filter;
				$name_filter = $filter->html_filter($_POST["name_filter"]);
				$cat = $filter->html_filter($_POST["cat"]);
				$act = $filter->html_filter($_POST["act"]);
				$inscroll = $filter->html_filter($_POST["inscroll"]);
				$intop = $filter->html_filter($_POST["intop"]);
				if (!empty($name_filter)) $query_filter.= " AND `name_goods` LIKE ('%".$name_filter."%')";
				if (!empty($cat)) $query_filter.= " AND `cat` = '".$cat."'";
				if (!empty($code_filter)) $query_filter.= " AND `code` ='".$code_filter."'";
				if (@$_POST["emptcode"]) $query_filter.= " AND `code`=''";
				if (@$_POST["emptprice"]) $query_filter.= " AND `price`=''";
				if (@$_POST["inlider"]) $query_filter.= " AND `popular`='1'";
				if (@$_POST["inscroll"]) $query_filter.= " AND `scroll`='1'";
				
			}
			else 
			{
				if (isset($_GET["cat"]) &&is_numeric($_GET["cat"])) $query_filter.= " AND `cat` = '".$cat."'"; else $query_filter = "";
			}


			// запросы для постраничной навигации
			$num = $config["catalog"]["page_in_admin"]; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_goods) FROM mod_catalog WHERE id_goods!=0".$query_filter; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_goods)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			$Db->query="SELECT mod_catalog.*,mod_catalog_cat.name_cat FROM `mod_catalog`
						LEFT JOIN `mod_catalog_cat` ON (mod_catalog.cat=mod_catalog_cat.id_cat)
						WHERE id_goods!=0".$query_filter." 
						ORDER BY mod_catalog.id_goods DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<form method="post" action="index.php?mod=catalog&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td width="60">Фото</td><td>Название</td><td></td><td width="60"></td><td width="60" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
			
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if (empty($lRes["img_good"])) $img = '<img src="images/empty.jpg" width="50" alt="Нет фото" />'; else $img = '<img src="/upload/goods/sm'.$lRes["img_good"].'.jpg" alt="'.$lRes["img_good"].'" width="50" />';
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					if (@$cat) $cat_page = "&cat=".$cat; else $cat_page = ""; 
					$content_mod.= '<tr class="one_news">
								<td>'.$img.'</td>
								<td><a href="index.php?mod=catalog&action=edit&id='.$lRes["id_goods"].$pagestr.$cat_page.'">'.$lRes["name_goods"].'</a></td>
								<td>'.$lRes["name_cat"].'</td>
								<td></td>
								<td><div class="conf">';
					
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_goods']."]' />";
		if ($lRes['act']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_goods']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_goods']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_goods']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				$content_mod.= '<tr><td></td><td></td><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
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

for ($i=1; $i<=$total; $i++) { 
if (@$cat) $cat_page = "&cat=".$cat; else $cat_page = ""; 
if ($page!=$i) $navi.='<a href=index.php?mod=catalog&action=list'.$cat_page.'&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
}
				if ($total > 1)	$navigation="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";
				$content_mod.= $navigation;
			}
			else
			{
				$content_mod.= "Товаров нет.";
			}
}
else
{
	if ($action=="add_filter_in_goods")
	{
		$Db->query="INSERT INTO `filter_goods` (`filter_goods_rel`,`goods_id`) VALUES ('".$filter."','".$goods."')"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$goods."#top'></head></html>");
	}
	if ($action=="delete_filter_in_goods")
	{
        $Db->query="SELECT `goods_id` FROM `filter_goods` WHERE `id_filter_goods`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="DELETE FROM `filter_goods` WHERE `id_filter_goods` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$lRes[goods_id]."'></head></html>");
	}
	if ($action=="list_filter")
	{
		
			
			$Db->query="SELECT * FROM `filter` ORDER BY `id_filter`";
			$Db->query();
			$rank = mysql_num_rows($Db->lQueryResult);
			if ($rank>0) {
				$content_mod.= '<form method="post" action="index.php?mod=catalog&action=pereschet_filter" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название параметра</td><td width="30" class="nobg"><div class="conf"><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
			
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=catalog&action=edit_filter&id='.$lRes["id_filter"].'">'.$lRes["name_filter"].'</a></td>
								<td><div class="conf">
								<input type="checkbox" value="1" name="delete['.$lRes['id_filter'].']" class="checkbox"  />
								</div></td>
								</tr>';
								$num++;

				}
				
				
				$content_mod.= '<tr><td><input src="img/icons/tick_red_icon.png" align="right" class="pnghack" type="image" hspace="7" /></td><td>
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
				});
			</script>
			<div class="conf"><input type="checkbox" value="1" class="checkbox" id="selall" /></div></td></tr></table></form>';
			}
			else
			{
				$content_mod = "Параметров нет.";
			}
	}
	
	if ($action=="edit_podfilter")
		{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `filter_params` WHERE `id_params`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($id!="new") $catnow = $lRes['filter_rel']; else $catnow = $cat;
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_params'])).'" size="50"></td>
  			</tr>
			</table>
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="cat" value="'.$catnow.'">
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$text = mysql_escape_string($_POST["text"]);
			$id = $_POST["id"];
			$cat = $_POST["cat"];
			$Db->query="INSERT INTO `filter_params` (`id_params`, `name_params`, `filter_rel`)
						VALUES ('".$id."','".$name."','".$cat."')
						ON DUPLICATE KEY UPDATE
						`id_params`=VALUES(`id_params`),
						`name_params`=VALUES(`name_params`),
						`filter_rel`=VALUES(`filter_rel`)";
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit_filter&id=".$cat."'></head></html>");
			
		}

	}
	if ($action=="delete_filter")
	{
		$Db->query="SELECT `filter_rel` FROM `filter_params` WHERE `id_params`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$filter_rel = $lRes["filter_rel"];
		
        $Db->query="DELETE FROM `filter_params` WHERE `id_params` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit_filter&id=".$filter_rel."'></head></html>");
	}
	if ($action=="edit_filter")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `filter` WHERE `id_filter`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название параметра:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_filter'])).'" size="50"> 						</td>
  			</tr>
			</table>
			<input type="hidden" name="id" value="'.$id.'">
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
			if ($id!="new") {
			$content_mod.= '<hr /><div class="add"><a href="index.php?mod=catalog&action=edit_podfilter&id=new&cat='.$id.'" class="red"><img src="images/plus.png" class="pnghack" align="middle" border="0" /> Добавить новое</a></div><h4><strong>Список параметра:</strong></h4>';
				$Db->query="SELECT `id_params`,`name_params` FROM `filter_params` WHERE `filter_rel`='".$id."' ORDER BY `name_params`";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
					$content_mod.= '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr class="head_news">
								<td>Название</td>
								<td width="50"></td>
								</tr>';
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=filter&action=edit_filter&id='.$lRes["id_params"].'">'.$lRes["name_params"].'</a></td>
								<td>
								<a href="#" onclick="Delete_filter_params('.$lRes["id_params"].')" title="Удалить"><img src="images/del.png" class="pnghack" /></a>
								</td>
								</tr>';
						}
						$content_mod.= "</table>";
					}
					else
					{
					$content_mod.= "Список пуст";
					}
			}

		}
		else //обрабатываем форму
		{
			
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$id = $_POST["id"];
			$Db->query="INSERT INTO `filter` (`id_filter`, `name_filter`)
						VALUES ('".$id."','".$name."')
						ON DUPLICATE KEY UPDATE
						`id_filter`=VALUES(`id_filter`),
						`name_filter`=VALUES(`name_filter`)";
			$content_mod.= "<br /><br /><p align='center'><img src='/images/loader.gif' /></p>";
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list_filter'></head></html>");
			
		}
	}
	if ($action=="list_cat")
	{
		$Db->query="SELECT `id_cat`,`name_cat`,`act`,`parent`,`rank` FROM `mod_catalog_cat` ORDER BY parent,rank";
			$Db->query();
			if(mysql_num_rows($Db->lQueryResult)>0) 
			{
				$filter = new filter; 
				$id = $filter->html_filter($_GET["down_cat"]);
				if (empty($id) or !is_numeric($id)) $current_cat = '0'; else $current_cat = $id;
				while($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					$data_cat[$lRes['parent']][] = $lRes;

				$data_cat = getTreeMod($data_cat, 0);
				
				$content_mod.= forech_pages($data_cat, 0, '');
				$content_mod.= "</div>";
			}
			else
				$content_mod = "Разделов нет";
	}
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_catalog` WHERE id_goods='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if (@$cat)  $myparent = $cat; else $myparent = $lRes['cat'];
			if ($lRes['act']==1) $chek = " checked";
			if ($lRes['popular']==1) $chek_popular = " checked";
			if ($lRes['hot']==1) $chek_hot = " checked";
			if ($lRes['brand']==1) $chek_brand = " checked";
			if ($lRes['volume']==1) $chek_volume = " checked";
			
			if ($lRes['avail']==0) $chek_avail_no = ' checked="checked"'; else $chek_avail_yes = ' checked="checked"';	
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			if (@$cat) $cat_page = "&cat=".$cat; else $cat_page = ""; 
			
			$img=$lRes['img_good'];
			$text_goods=$lRes['text_goods'];
			
			$my_brand=$lRes[brand];
			$content_mod.= '<form method="post" enctype="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_goods'])).'" size="80"> </td>
  			</tr>
  			<tr height="30">
    		<td width="40%"><p>Код товара:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="code" value="'.htmlspecialchars(stripslashes($lRes['code'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td><p>Браузерный заголовок:<br />
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
    		<td width="40%"><p>Цена:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="price" id="quantity" value="'.htmlspecialchars(stripslashes($lRes['price'])).'" size="10"> руб. <span id="errmsg"></span></td>
  			</tr>
			
			';
			
			/*
			';
			<tr height="30">
    		<td width="40%"><p>Старая цена:<br />
			<span class="smallgray">сокращённо, например кг., л., шт., уп.</span></p></td>
    		<td width="60%">&nbsp;<input type="text" name="units" value="'.htmlspecialchars(stripslashes($lRes['units'])).'" size="10"> руб. </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Артикул:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="code" value="'.htmlspecialchars(stripslashes($lRes['code'])).'" size="80"> </td>
  			</tr>
			
			<tr height="30">
    		<td width="40%"><p>Остаток:</p></td>
    		<td width="60%">&nbsp;<input type="text" name="num" value="'.htmlspecialchars(stripslashes($lRes['num'])).'" size="10"></td>
  			</tr>
			*/
			
			$content_mod.= '<tr height="30">
    		<td width="40%"><p>Обложка товара:<br />
			<span class="smallgray">формат JPG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($img)) $content_mod.='<input name="image" type="file">'; else $content_mod.="<img src='/upload/goods/sm".$img.".jpg' align=middle hspace=10><a title='Удалить картинку' href='#' OnClick='Delete_good_cover(".$id.")'><img src='images/act_no.jpg' border='0' /></a><input type='hidden' name='img_load' value='".$img."'>";
			$content_mod.= '</td>
  			</tr>
			</table>';
			$content_mod.= '<p>Анонс товара</p><br /><textarea name="teh" class="texta">'.htmlspecialchars(stripslashes($lRes['text_teh'])).'</textarea> <br />
			<p>Полное описание товара</p><br />
			<textarea name="anons" class="texta">'.htmlspecialchars(stripslashes($text_goods)).'</textarea> <br />';
						
			$content_mod.= '<p><br />Категория <select name="cat">
			<option value="0">Нет категории</option>';
			$Db->query="SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent` FROM `mod_catalog_cat` WHERE `id_cat`!='0' ORDER BY `parent`,`rank`";
			$Db->query();
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data[$lRes['parent']][] = $lRes;
			$data = getTreeMod($data, 0);
			$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select></p>';

			$content_mod.= '<br /><h4><strong>Дополнительные фотографии:</strong></h4><br />';
				$Db->query="SELECT `id_file`,`source` FROM `mod_file` WHERE `good`='".$id."'";
				$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $content_mod.= '<img src="/upload/dop_goods/sm'.$lRes["source"].'.jpg" hspace="5" align="middle" width="100" /><a title="Удалить картинку" href="#" OnClick="Delete_goods_img('.$lRes["id_file"].')"><img src="images/act_no.jpg" border="0" /></a>'; else $content_mod.= "Изображений нет";
			$content_mod.= 	'<br /><br /><input type="file" class="multi" name="fileToUpload[]" />';
				
			//фильтрация товаров
	$content_mod.= '<br /><h4><strong>Параметры фильтрации:</strong></h4><br />';
			if ($id!="new") {
					//выясняем общее кол-во фильтров
					$Db->query="SELECT COUNT(id_filter) FROM filter";
					$Db->query();
					$lRes=mysql_fetch_assoc($Db->lQueryResult);
					$count = $lRes["COUNT(id_filter)"];
					
					//выясняем подключены ли фильтры к данному товару
					$Db->query="SELECT * FROM filter 
								LEFT JOIN filter_goods ON filter.id_filter=filter_goods.filter_goods_rel 
								WHERE filter_goods.goods_id = '".$id."'";
					$Db->query();
					//если таковые имеются выводим их
		
					if (mysql_num_rows($Db->lQueryResult)>0) 
					{
					$num = 0;
					$query_not = "(";
						while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
						{
						$id_filter = $lRes["id_filter"];
						$params_rel = $lRes["params_rel"];
						$content_mod.= '<p><a href="#" OnClick="delete_filter_in_goods('.$lRes['id_filter_goods'].')"><img src="images/actno.png" class="pnghack" border="0" alt="Удалить" align="middle" /></a>'.$lRes["name_filter"].'
						<select name="filter['.$lRes["id_filter"].']"><option value="0">Не выбрано</option>';
							$temp_query = mysql_query("SELECT * FROM filter_params WHERE filter_rel = '".$lRes["filter_goods_rel"]."' ORDER BY `name_params`");
							
							while ($temp_res=mysql_fetch_assoc($temp_query)) 
							{
								$content_mod.= '<option value="'.$temp_res["id_params"].'"';
								if ($params_rel==$temp_res["id_params"]) $content_mod.= ' selected="selected"';
								$content_mod.= '>'.$temp_res["name_params"].'</option>';
							}
							$content_mod.= '</select></p>';
							$num++;
							$query_not.= $id_filter.",";
						}
						//если подключены не все фильтры выводим возможность подключения оставшихся
						if ($count!=$num) {
							$query_not = substr($query_not, 0, strlen($query_not) - 1 ). ")";
							$Db->query="SELECT * FROM filter WHERE id_filter NOT IN ".$query_not;
							$Db->query();
							while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
							{
							$content_mod.= '<br /><p><strong>'.$lRes["name_filter"].'</strong> <a href="index.php?mod=catalog&action=add_filter_in_goods&filter='.$lRes['id_filter'].'&goods='.$id.'" class="red">+ добавить параметр к товару</a></p>';
							}
						}
					}
					//если ниодного фильтра не нашли выводим возможность подключения всех
					else 
					{
					$Db->query="SELECT * FROM filter";
					$Db->query();
						if (mysql_num_rows($Db->lQueryResult)>0) {
						while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
							{
							$content_mod.= '<p><strong>'.$lRes["name_filter"].'</strong> <a href="index.php?mod=catalog&action=add_filter_in_goods&filter='.$lRes['id_filter'].'&goods='.$id.'" class="red">+ добавить параметр к товару</a></p>';
							}
						}
					}
				}
		else
		{
			$content_mod.= 'Доступно только в режиме редактирования.';
		}			
				
			$content_mod.= '
			<input type="hidden" name="id" value="'.$id.'">
			<input type="hidden" name="page" value="'.$page.'">
			<input type="hidden" name="cat_id" value="'.$cat.'">
			<br /><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br />
			<input class="check" name="hot" type="checkbox"'.$chek_hot.' value="on" /> В супер предложения<br />
			<!--<input class="check" name="popular" type="checkbox"'.$chek_popular.' value="on" /> Вывести на главную<br />
			<input class="check" name="volume" type="checkbox"'.$chek_volume.' value="on" /> Распродажа<br />-->
			<input class="check" name="brand" type="checkbox"'.$chek_brand.' value="on" /> Отметка акция<br />
			<!--<input name="avail" type="radio" value="0"'.$chek_avail_no.' /> Нет в наличии <input name="avail" type="radio" value="1"'.$chek_avail_yes.' /> Есть в наличии<br />-->
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form>';
						
		}
		else //обрабатываем форму
		{
			if (!isset($_POST['img_load'])) { //сохранение обложки товара
				if (!empty($_FILES["image"]["name"])){
					$source=$_FILES["image"]["tmp_name"];
					$myrand = rand();
					$img_name_full = "/upload/goods/sm".$myrand.".jpg";
					$img_name_full_slider = "/upload/goods/sl".$myrand.".jpg";
					$img_name_full_large = "/upload/goods/bg".$myrand.".jpg";
					
					//create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, $thumb_width=280, $thumb_height=280, $do_cut=false);
					imgResize($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full, 97, 97, 0xFFFFFF, 100);	
					//create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full_slider, $thumb_width=176, $thumb_height=177, $do_cut=false);	
					create_thumbnail($source, $_SERVER['DOCUMENT_ROOT'].$img_name_full_large, $thumb_width=800, $thumb_height=600, $do_cut=false);	
					$img_name = $myrand;
				}
				else {$img_name = "";}
			}
			else {$img_name = $_POST['img_load'];}

			// остальные данные
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			$code = $filter->html_filter($_POST["code"]);
			//$rate = $filter->html_filter($_POST["rate"]);
			$price = str_replace('.','.',$filter->html_filter($_POST["price"]));
			$price = str_replace(',','.',$filter->html_filter($price));
			$units = $filter->html_filter($_POST["units"]);
			
			//$oldprice = $filter->html_filter($_POST["oldprice"]);
			
			$title = $filter->html_filter($_POST["title"]);
			$keys = $filter->html_filter($_POST["keys"]);
			$meta = $filter->html_filter($_POST["meta"]);
			$text = mysql_escape_string($_POST["anons"]);
			$teh = mysql_escape_string($_POST["teh"]);
			
			//$video = mysql_escape_string($_POST["video"]);
						
			$id = $_POST["id"];
			$cat = $_POST["cat"];
			$avail = $_POST["avail"];
			
			if(@$_POST["act"])
					$act = 1;
			else
					$act = 0;									
			
			if(@$_POST["hot"])
					$hot = 1;
			else
					$hot = 0;	
			
			if(@$_POST["popular"])
					$popular = 1;
			else
					$popular = 0;
					
			if(@$_POST["brand"])
					$brand = 1;
			else
					$brand = 0;
					
			if(@$_POST["volume"])
					$volume = 1;
			else
					$volume = 0;
			/*
			$Db->query="SELECT `count_goods` FROM `mod_catalog_cat` WHERE `id_cat`='".$cat."'";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			echo $tmp_val=$lRes['count_goods']+1;
			$Db->query="UPDATE `mod_catalog_cat` SET `count_goods`='".$tmp_val."' WHERE `id_cat`='".$cat."'"; 
			$Db->query();*/
			
			if (@$_POST["filter"]) {
				$query = '';
				foreach ($_POST["filter"] as $key=>$value) $query.= " WHEN `filter_goods_rel`='".$key."' AND `goods_id`='".$id."' THEN '".$value."'";
				$Db->query="UPDATE `filter_goods` 
				SET `params_rel` = CASE ".$query."
				ELSE `params_rel` END";
				$Db->query(); 
			}	
			
			$anchor = trans(stripslashes(trim($_POST["name"])));
			$Db->query="INSERT INTO `mod_catalog` 
						(`id_goods`, `name_goods`,`code`,`anchor_goods`,`img_good`,`text_goods`, `price`,`cat`,  `act`, `title`, `keys`, `meta`,`anons`,`edit_date`,`edit_who`,`avail`,`units`,`hot`,`popular`,`brand`, `text_teh`, `volume`)
						VALUES 
						('".$id."', '".$name."','".$code."','".$anchor."','".$img_name."','".$text."', '".$price."','".$cat."', '".$act."', '".$title."', '".$keys."', '".$meta."', 'empty', NOW(),'".$_SESSION['id_user']."','".$avail."','".$units."','".$hot."','".$popular."','".$brand."','".$teh."','".$volume."')
						ON DUPLICATE KEY UPDATE
						`id_goods`=VALUES(`id_goods`),
						`name_goods`=VALUES(`name_goods`),
						`code`=VALUES(`code`),
						`anchor_goods`=VALUES(`anchor_goods`),
						`img_good`=VALUES(`img_good`),
						`text_goods`=VALUES(`text_goods`),
						`price`=VALUES(`price`),
						`cat`=VALUES(`cat`),
						`act`=VALUES(`act`),
						`title`=VALUES(`title`),
						`keys`=VALUES(`keys`),
						`meta`=VALUES(`meta`),
						`anons`=VALUES(`anons`),
						`edit_date`=VALUES(`edit_date`),
						`edit_who`=VALUES(`edit_who`),
						`avail`=VALUES(`avail`),
						`units`=VALUES(`units`),
						`hot`=VALUES(`hot`),
						`popular`=VALUES(`popular`),
						`brand`=VALUES(`brand`),
						`text_teh`=VALUES(`text_teh`),
						`volume`=VALUES(`volume`)";
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
								$myname_full = "/upload/dop_goods/sm".$myname.".jpg";
								$myname_full_large = "/upload/dop_goods/bg".$myname.".jpg";								
								imgResize($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full, 280, 280, 0xFFFFFF, 100);
create_thumbnail($_FILES[$fileElementName]['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'].$myname_full_large, $thumb_width=800, $thumb_height=600, $do_cut=false);
								$msg.= $myname."|";
								@unlink($_FILES[$fileElementName][$i]);		
							}
						}
					}
					if (!empty($msg)) {
						if ($_POST["id"]=="new") $id = mysql_insert_id(); else $id = $_POST["id"];
						$file = explode("|", substr($msg,0,-1));
						$input = "";
						foreach ($file as $key=>$value) $input.= "('".$value."','".$id."'),";
						$input = substr($input,0,-1);
						$Db->query="INSERT INTO `mod_file` (`source`,`good`) VALUES ".$input;
						$Db->query();
					}		
				
						
			if ($_POST["id"]=="new")
			{
				$Db->query="INSERT INTO `mod_stat` (`name`, `date`) VALUES ('Новый товар \"".$name."\"', NOW())";
				$Db->query(); 				
			}
			
			if (@$_POST["page"]) $pagestr = "&page=".$_POST["page"]; else $pagestr = "";
			if (@$_POST["cat_id"]) $cat_page = "&cat=".$_POST["cat_id"]; else $cat_page = "";
							
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list".$pagestr.$cat_page."'></head></html>");
		}
	}
	
	if ($action=="edit_cat")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_catalog_cat` WHERE id_cat='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			$myparent = $lRes['parent'];
			$text = $lRes['text_cat'];
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Название:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_cat'])).'" size="80"> 						</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ссылка:</p></td>
    		<td width="60%"><input type="text" name="anchor" value="'.htmlspecialchars(stripslashes($lRes['anchor_cat'])).'" size="80"></td>
  			</tr>
			<tr height="30">
    		<td><p>Заголовок страницы:<br />
			<span class="smallgray">отображается в верхней статусной строке браузера</span></p> </td>
    		<td><input type="text" name="title" value="'.htmlspecialchars(stripslashes($lRes['title'])).'" size="80"></td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Ключевые слова:<br />
			<span class="smallgray">через запятую, максимум 255 символов</span></p></td>
    		<td width="60%"><input type="text" name="keys" value="'.htmlspecialchars(stripslashes($lRes['keys'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Краткое описание:<br />
			<span class="smallgray">для поисковиков, максимум 255 символов</span></p></td>
    		<td width="60%"><input type="text" name="meta" value="'.htmlspecialchars(stripslashes($lRes['meta'])).'" size="80"> </td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Позиция:</p></td>
    		<td width="60%"><input type="text" name="rank" value="'.htmlspecialchars(stripslashes($lRes['rank'])).'" size="80"> 						</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Картинка (опционально):<br />
			<span class="smallgray">формат PNG</span></p></td>
    		<td width="60%">';
			if($id=="new" or empty($lRes['img_cat'])) $content_mod.='<input name="image" type="file">'; else $content_mod.="<img src='/upload/cat/".$lRes['img_cat'].".png' width='100' align=middle hspace=10 border=0><a title='Удалить картинку' href='#' OnClick='Delete_cat_img(".$lRes['id_cat'].")'><img src='images/act_no.jpg' border='0' border=0 /></a><input type='hidden' name='img_load' value='".$lRes['img_cat']."'>";
			$content_mod.= '</td>
  			</tr></table>
			<input type="hidden" name="oldcat" value="'.$myparent.'"><br />
			<p>Родительская категория <select name="cat">
			<option value="0">Корень сайта</option>';
				$Db->query = "SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent` FROM `mod_catalog_cat` WHERE `id_cat`!='".$id."' ORDER BY `parent`,`rank`";
				$Db->query();
				while($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					$data[$lRes['parent']][] = $lRes;
					
				$data = getTreeMod($data, 0);
				$content_mod.= forech_pages_select($data, $myparent, "", "--");
			$content_mod.= '</select></p><br />
			<p>Описание</p><br />
			<textarea name="text" class="texta">'.htmlspecialchars(stripslashes($text)).'</textarea> 
			<input type="hidden" name="id" value="'.$id.'"><br />
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br />
			';
			
			$content_mod.= '<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
		}
		else //обрабатываем форму
			{
				if(!isset($_POST['img_load'])) //сохранение обложки товара
				{
					if(!empty($_FILES["image"]["name"]))
					{
						$source=$_FILES["image"]["tmp_name"];
						$myrand = rand();
						move_uploaded_file($source, $_SERVER['DOCUMENT_ROOT']."/upload/cat/".$myrand.".png");
						$img_name_full = $myrand;
					}
					else $img_name_full = "";
				}
				else $img_name_full = $_POST['img_load'];

				
				$filter = new filter; 
				$name = $filter->html_filter($_POST["name"]);
				$anons = $filter->html_filter($_POST["anons"]);
				$title = $filter->html_filter($_POST["title"]);
				$keys = $filter->html_filter($_POST["keys"]);
				$meta = $filter->html_filter($_POST["meta"]);
				$text = mysql_escape_string($_POST["text"]);
				$id = $_POST["id"];
				$cat = $_POST["cat"];
				//echo $_POST["cat"];
				//if (empty($_POST["anchor"])) $anchor = trans($_POST["name"]); else $anchor = $filter->html_filter($_POST["anchor"]);
				$anchor = trans($_POST["name"]);
				
				if(@$_POST["act"])
					$act = 1;
				else
					$act = 0;

				$rank = $filter->html_filter($_POST["rank"]); 
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
				$Db->query();
				if ($_POST["id"]=="new")
				{
					$Db->query="INSERT INTO `mod_stat` (`name`, `date`) VALUES ('Новая категория \"".$name."\"', NOW())";
					$Db->query(); 
					
				}
				exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list_cat'></head></html>");
					
			}
	}
	
	
	if ($action=="pereschet_cat")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `mod_catalog_cat` WHERE `id_cat` IN ".$query;
			 $Db->query();
		}
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_catalog_cat SET `act` = '".$val."' WHERE `id_cat` ='".$key."'";
				 $Db->query();
			 }
		}
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list_cat'></head></html>");
	}
	if ($action=="pereschet_filter")
	{

		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `filter` WHERE `id_filter` IN ".$query;
			 $Db->query();
		}
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list_filter'></head></html>");
	}
	if ($action=="delete_dop")
	{
        $Db->query="DELETE FROM `mod_dop` WHERE `id_dop` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$good."#tut'></head></html>");
	}
	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		
		if(!empty($_POST["move"]))
		{
			 foreach($_POST["move"] as $key=>$val) 
			 {
					$Db->query="SELECT * FROM `mod_catalog` WHERE `id_goods` = '".$key."'"; 
					$Db->query();
					$lRes=mysql_fetch_assoc($Db->lQueryResult);
					$Db->query="INSERT INTO mod_catalog (`name_goods`,`code`,`anchor_goods`,`img_good`,`text_goods`,`text_teh`, `price`,`cat`, `popular`,`act`,`title`,`keys`,`meta`,`anons`,`avail`,`edit_date`,`edit_who`) VALUES ('".$lRes[name_goods]."','".$lRes[code]."','".$lRes[anchor_goods]."','".$lRes[img_good]."','".$lRes[text_goods]."','".$lRes[text_teh]."','".$lRes[price]."','219','".$lRes[popular]."','1','".$lRes[title]."','".$lRes[keys]."','".$lRes[meta]."','".$lRes[anons]."','".$lRes[avail]."',NOW(),'".$_SESSION['id_user']."')";
					$Db->query();
			 }
		}

	
		
		if(!empty($_POST["delete"]))
		{
			
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "'$key',";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 
			 $Db->query="SELECT `img_good` FROM `mod_catalog` WHERE `id_goods` IN ".$query;
			 $Db->query();
			 while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
			{
				if (!empty($lRes["img_good"]))
				{ 
					unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/bg".$lRes["img_good"].".jpg");
					unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sm".$lRes["img_good"].".jpg");
					unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sl".$lRes["img_good"].".jpg");
				}
			}
			$tmp_src = "(" ;
			$Db->query="SELECT `source` FROM `mod_file` WHERE `good` IN ".$query;
			$Db->query();
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
			 {
				$tmp_src.="'$lRes[source]',";
				unlink($_SERVER['DOCUMENT_ROOT']."/upload/dop_goods/sm".$lRes['source'].".jpg");
				unlink($_SERVER['DOCUMENT_ROOT']."/upload/dop_goods/bg".$lRes['source'].".jpg");	
				//unlink($_SERVER['DOCUMENT_ROOT']."/upload/dop_goods/med".$lRes['source'].".jpg");					
			 }
			if ($tmp_src!='(') {
			$tmp_src = substr($tmp_src, 0, strlen($tmp_src) - 1 ). ")" ;
			$Db->query="DELETE FROM `mod_file` WHERE `source` IN ".$tmp_src; 
			$Db->query();
			 }
			$Db->query="DELETE FROM `mod_catalog` WHERE `id_goods` IN ".$query;
			$Db->query();			 
		}
		
		if(!empty($_POST["act"]))
		{
			 foreach($_POST["act"] as $key=>$val) 
			 {
				 $Db->query="UPDATE mod_catalog SET `act` = '".$val."' WHERE `id_goods` ='".$key."'";
				 $Db->query();
			 }
		}

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list'></head></html>");
	}
		
	if ($action=="delete_catalog_logo")
	{
        $Db->query="SELECT `logo` FROM `mod_catalog` WHERE `id_goods`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_catalog` SET `logo`='' WHERE `id_goods` = '".$id."'"; 
		$Db->query();

		if (@$lRes["logo"] && file_exists($_SERVER['DOCUMENT_ROOT']."/upload/catalog/logo".$lRes["logo"].".jpg")) unlink($_SERVER['DOCUMENT_ROOT']."/upload/catalog/logo".$lRes[logo].".jpg");

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$id."'></head></html>");
	}
	if ($action=="delete_img")
	{
        $Db->query="SELECT `img_good` FROM `mod_catalog` WHERE `id_goods`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_catalog` SET `img_good`='' WHERE `id_goods` = '".$id."'"; 
		$Db->query();

		if (@$lRes["img_good"] && file_exists($_SERVER['DOCUMENT_ROOT']."/upload/goods/bg".$lRes["img_good"].".jpg")) unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/bg".$lRes["img_good"].".jpg");
		if (@$lRes["img_good"] && file_exists($_SERVER['DOCUMENT_ROOT']."/upload/goods/sm".$lRes["img_good"].".jpg")) unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sm".$lRes["img_good"].".jpg");
		if (@$lRes["img_good"] && file_exists($_SERVER['DOCUMENT_ROOT']."/upload/goods/sl".$lRes["img_good"].".jpg")) unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sl".$lRes["img_good"].".jpg");
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$id."'></head></html>");
	}
	if ($action=="delete_cat_img")
	{
        $Db->query="SELECT `img_cat` FROM `mod_catalog_cat` WHERE `id_cat`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_catalog_cat` SET `img_cat`='' WHERE `id_cat` = '".$id."'"; 
		$Db->query();

		if (!empty($lRes['img_cat'])) unlink($_SERVER['DOCUMENT_ROOT']."/upload/cat/".$lRes['img_cat'].".png");

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit_cat&id=".$id."'></head></html>");
	}
	
	if ($action=="delete_good_cover")
	{
        $Db->query="SELECT `img_good` FROM `mod_catalog` WHERE `id_goods`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="UPDATE `mod_catalog` SET `img_good`='' WHERE `id_goods` = '".$id."'"; 
		$Db->query();

		if (!empty($lRes['img_good'])) {
			unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sm".$lRes['img_good'].".jpg");
			unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/bg".$lRes['img_good'].".jpg");
			unlink($_SERVER['DOCUMENT_ROOT']."/upload/goods/sl".$lRes['img_good'].".jpg");
		};

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$id."'></head></html>");
	}

	if ($action=="delete_goods_img")
	{
        $Db->query="SELECT `source`, `good` FROM `mod_file` WHERE `id_file`='".$id."'";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$Db->query="DELETE FROM `mod_file` WHERE `id_file` = '".$id."'"; 
		$Db->query();

		if (!empty($lRes['source'])) {
			unlink($_SERVER['DOCUMENT_ROOT']."/upload/dop_goods/sm".$lRes['source'].".jpg");
			unlink($_SERVER['DOCUMENT_ROOT']."/upload/dop_goods/bg".$lRes['source'].".jpg");
		};

		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=edit&id=".$lRes['good']."'></head></html>");
	}
	


	if ($action=="import")
	{
			if (isset($_POST['submit'])) {
			define('NUM_BIG_BLOCK_DEPOT_BLOCKS_POS', 0x2c);
			define('SMALL_BLOCK_DEPOT_BLOCK_POS', 0x3c);
			define('ROOT_START_BLOCK_POS', 0x30);
			define('BIG_BLOCK_SIZE', 0x200);
			define('SMALL_BLOCK_SIZE', 0x40);
			define('EXTENSION_BLOCK_POS', 0x44);
			define('NUM_EXTENSION_BLOCK_POS', 0x48);
			define('PROPERTY_STORAGE_BLOCK_SIZE', 0x80);
			define('BIG_BLOCK_DEPOT_BLOCKS_POS', 0x4c);
			define('SMALL_BLOCK_THRESHOLD', 0x1000);
			define('SIZE_OF_NAME_POS', 0x40);
			define('TYPE_POS', 0x42);
			define('START_BLOCK_POS', 0x74);
			define('SIZE_POS', 0x78);
			define('IDENTIFIER_OLE', pack("CCCCCCCC",0xd0,0xcf,0x11,0xe0,0xa1,0xb1,0x1a,0xe1));
			define('SPREADSHEET_EXCEL_READER_BIFF8', 0x600);
			define('SPREADSHEET_EXCEL_READER_BIFF7', 0x500);
			define('SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS', 0x5);
			define('SPREADSHEET_EXCEL_READER_WORKSHEET', 0x10);
			define('SPREADSHEET_EXCEL_READER_TYPE_BOF', 0x809);
			define('SPREADSHEET_EXCEL_READER_TYPE_EOF', 0x0a);
			define('SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET', 0x85);
			define('SPREADSHEET_EXCEL_READER_TYPE_DIMENSION', 0x200);
			define('SPREADSHEET_EXCEL_READER_TYPE_ROW', 0x208);
			define('SPREADSHEET_EXCEL_READER_TYPE_DBCELL', 0xd7);
			define('SPREADSHEET_EXCEL_READER_TYPE_FILEPASS', 0x2f);
			define('SPREADSHEET_EXCEL_READER_TYPE_NOTE', 0x1c);
			define('SPREADSHEET_EXCEL_READER_TYPE_TXO', 0x1b6);
			define('SPREADSHEET_EXCEL_READER_TYPE_RK', 0x7e);
			define('SPREADSHEET_EXCEL_READER_TYPE_RK2', 0x27e);
			define('SPREADSHEET_EXCEL_READER_TYPE_MULRK', 0xbd);
			define('SPREADSHEET_EXCEL_READER_TYPE_MULBLANK', 0xbe);
			define('SPREADSHEET_EXCEL_READER_TYPE_INDEX', 0x20b);
			define('SPREADSHEET_EXCEL_READER_TYPE_SST', 0xfc);
			define('SPREADSHEET_EXCEL_READER_TYPE_EXTSST', 0xff);
			define('SPREADSHEET_EXCEL_READER_TYPE_CONTINUE', 0x3c);
			define('SPREADSHEET_EXCEL_READER_TYPE_LABEL', 0x204);
			define('SPREADSHEET_EXCEL_READER_TYPE_LABELSST', 0xfd);
			define('SPREADSHEET_EXCEL_READER_TYPE_NUMBER', 0x203);
			define('SPREADSHEET_EXCEL_READER_TYPE_NAME', 0x18);
			define('SPREADSHEET_EXCEL_READER_TYPE_ARRAY', 0x221);
			define('SPREADSHEET_EXCEL_READER_TYPE_STRING', 0x207);
			define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA', 0x406);
			define('SPREADSHEET_EXCEL_READER_TYPE_FORMULA2', 0x6);
			define('SPREADSHEET_EXCEL_READER_TYPE_FORMAT', 0x41e);
			define('SPREADSHEET_EXCEL_READER_TYPE_XF', 0xe0);
			define('SPREADSHEET_EXCEL_READER_TYPE_BOOLERR', 0x205);
			define('SPREADSHEET_EXCEL_READER_TYPE_UNKNOWN', 0xffff);
			define('SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR', 0x22);
			define('SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS', 0xE5);
			define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS' , 25569);
			define('SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904', 24107);
			define('SPREADSHEET_EXCEL_READER_MSINADAY', 86400);
			define('SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT', "%s");
			function GetInt4d($data, $pos) {
				$value = ord($data[$pos]) | (ord($data[$pos+1])	<< 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
				if ($value>=4294967294) {
					$value=-2;
				}
				return $value;
			}
			class OLERead {
				var $data = '';
				function OLERead() {
					
					
				}
				function read($sFileName){
					if(!is_readable($sFileName)) {
						$this->error = 1;
						return false;
					}
					$this->data = @file_get_contents($sFileName);
					if (!$this->data) { 
						$this->error = 1; 
						return false; 
					}
					if (substr($this->data, 0, 8) != IDENTIFIER_OLE) {
						$this->error = 1; 
						return false; 
					}
					$this->numBigBlockDepotBlocks = GetInt4d($this->data, NUM_BIG_BLOCK_DEPOT_BLOCKS_POS);
					$this->sbdStartBlock = GetInt4d($this->data, SMALL_BLOCK_DEPOT_BLOCK_POS);
					$this->rootStartBlock = GetInt4d($this->data, ROOT_START_BLOCK_POS);
					$this->extensionBlock = GetInt4d($this->data, EXTENSION_BLOCK_POS);
					$this->numExtensionBlocks = GetInt4d($this->data, NUM_EXTENSION_BLOCK_POS);
					$bigBlockDepotBlocks = array();
					$pos = BIG_BLOCK_DEPOT_BLOCKS_POS;
				$bbdBlocks = $this->numBigBlockDepotBlocks;
					
						if ($this->numExtensionBlocks != 0) {
							$bbdBlocks = (BIG_BLOCK_SIZE - BIG_BLOCK_DEPOT_BLOCKS_POS)/4; 
						}
					
					for ($i = 0; $i < $bbdBlocks; $i++) {
						  $bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
						  $pos += 4;
					}
					
					
					for ($j = 0; $j < $this->numExtensionBlocks; $j++) {
						$pos = ($this->extensionBlock + 1) * BIG_BLOCK_SIZE;
						$blocksToRead = min($this->numBigBlockDepotBlocks - $bbdBlocks, BIG_BLOCK_SIZE / 4 - 1);

						for ($i = $bbdBlocks; $i < $bbdBlocks + $blocksToRead; $i++) {
							$bigBlockDepotBlocks[$i] = GetInt4d($this->data, $pos);
							$pos += 4;
						}   

						$bbdBlocks += $blocksToRead;
						if ($bbdBlocks < $this->numBigBlockDepotBlocks) {
							$this->extensionBlock = GetInt4d($this->data, $pos);
						}
					}
					$pos = 0;
					$index = 0;
					$this->bigBlockChain = array();
					
					for ($i = 0; $i < $this->numBigBlockDepotBlocks; $i++) {
						$pos = ($bigBlockDepotBlocks[$i] + 1) * BIG_BLOCK_SIZE;
						//echo "pos = $pos";	
						for ($j = 0 ; $j < BIG_BLOCK_SIZE / 4; $j++) {
							$this->bigBlockChain[$index] = GetInt4d($this->data, $pos);
							$pos += 4 ;
							$index++;
						}
					}
					$pos = 0;
					$index = 0;
					$sbdBlock = $this->sbdStartBlock;
					$this->smallBlockChain = array();
				
					while ($sbdBlock != -2) {
				
					  $pos = ($sbdBlock + 1) * BIG_BLOCK_SIZE;
				
					  for ($j = 0; $j < BIG_BLOCK_SIZE / 4; $j++) {
						$this->smallBlockChain[$index] = GetInt4d($this->data, $pos);
						$pos += 4;
						$index++;
					  }
				
					  $sbdBlock = $this->bigBlockChain[$sbdBlock];
					}
					$block = $this->rootStartBlock;
					$pos = 0;
					$this->entry = $this->__readData($block);
					$this->__readPropertySets();

				}
				
				 function __readData($bl) {
					$block = $bl;
					$pos = 0;
					$data = '';
					
					while ($block != -2)  {
						$pos = ($block + 1) * BIG_BLOCK_SIZE;
						$data = $data.substr($this->data, $pos, BIG_BLOCK_SIZE);
						//echo "pos = $pos data=$data\n";	
					$block = $this->bigBlockChain[$block];
					}
					return $data;
				 }
					
				function __readPropertySets(){
					$offset = 0;
					//var_dump($this->entry);
					while ($offset < strlen($this->entry)) {
						  $d = substr($this->entry, $offset, PROPERTY_STORAGE_BLOCK_SIZE);
						
						  $nameSize = ord($d[SIZE_OF_NAME_POS]) | (ord($d[SIZE_OF_NAME_POS+1]) << 8);
						  
						  $type = ord($d[TYPE_POS]);
						  //$maxBlock = strlen($d) / BIG_BLOCK_SIZE - 1;
					
						  $startBlock = GetInt4d($d, START_BLOCK_POS);
						  $size = GetInt4d($d, SIZE_POS);
					
						$name = '';
						for ($i = 0; $i < $nameSize ; $i++) {
						  $name .= $d[$i];
						}
						
						$name = str_replace("\x00", "", $name);
						
						$this->props[] = array (
							'name' => $name, 
							'type' => $type,
							'startBlock' => $startBlock,
							'size' => $size);

						if (($name == "Workbook") || ($name == "Book")) {
							$this->wrkbook = count($this->props) - 1;
						}

						if ($name == "Root Entry") {
							$this->rootentry = count($this->props) - 1;
						}
						$offset += PROPERTY_STORAGE_BLOCK_SIZE;
					}   
					
				}
				
				
				function getWorkBook(){
					if ($this->props[$this->wrkbook]['size'] < SMALL_BLOCK_THRESHOLD){
						$rootdata = $this->__readData($this->props[$this->rootentry]['startBlock']);
						$streamData = '';
						$block = $this->props[$this->wrkbook]['startBlock'];
						//$count = 0;
						$pos = 0;
						while ($block != -2) {
							  $pos = $block * SMALL_BLOCK_SIZE;
							  $streamData .= substr($rootdata, $pos, SMALL_BLOCK_SIZE);

							  $block = $this->smallBlockChain[$block];
						}
						
						return $streamData;
						

					}else{
					
						$numBlocks = $this->props[$this->wrkbook]['size'] / BIG_BLOCK_SIZE;
						if ($this->props[$this->wrkbook]['size'] % BIG_BLOCK_SIZE != 0) {
							$numBlocks++;
						}
						
						if ($numBlocks == 0) return '';
						$streamData = '';
						$block = $this->props[$this->wrkbook]['startBlock'];
						$pos = 0;
						while ($block != -2) {
						  $pos = ($block + 1) * BIG_BLOCK_SIZE;
						  $streamData .= substr($this->data, $pos, BIG_BLOCK_SIZE);
						  $block = $this->bigBlockChain[$block];
						}
						return $streamData;
					}
				}
				
			}
			class Spreadsheet_Excel_Reader {
				var $boundsheets = array();
				var $formatRecords = array();
				var $sst = array();
				var $sheets = array();
				var $data;
				var $_ole;
				var $_defaultEncoding;
				var $_defaultFormat = SPREADSHEET_EXCEL_READER_DEF_NUM_FORMAT;
				var $_columnsFormat = array();
				var $_rowoffset = 1;
				var $_coloffset = 1;
				var $dateFormats = array (
					0xe => "d/m/Y",
					0xf => "d-M-Y",
					0x10 => "d-M",
					0x11 => "M-Y",
					0x12 => "h:i a",
					0x13 => "h:i:s a",
					0x14 => "H:i",
					0x15 => "H:i:s",
					0x16 => "d/m/Y H:i",
					0x2d => "i:s",
					0x2e => "H:i:s",
					0x2f => "i:s.S");
				var $numberFormats = array(
					0x1 => "%1.0f",     // "0"
					0x2 => "%1.2f",     // "0.00",
					0x3 => "%1.0f",     //"#,##0",
					0x4 => "%1.2f",     //"#,##0.00",
					0x5 => "%1.0f",     /*"$#,##0;($#,##0)",*/
					0x6 => '$%1.0f',    /*"$#,##0;($#,##0)",*/
					0x7 => '$%1.2f',    //"$#,##0.00;($#,##0.00)",
					0x8 => '$%1.2f',    //"$#,##0.00;($#,##0.00)",
					0x9 => '%1.0f%%',   // "0%"
					0xa => '%1.2f%%',   // "0.00%"
					0xb => '%1.2f',     // 0.00E00",
					0x25 => '%1.0f',    // "#,##0;(#,##0)",
					0x26 => '%1.0f',    //"#,##0;(#,##0)",
					0x27 => '%1.2f',    //"#,##0.00;(#,##0.00)",
					0x28 => '%1.2f',    //"#,##0.00;(#,##0.00)",
					0x29 => '%1.0f',    //"#,##0;(#,##0)",
					0x2a => '$%1.0f',   //"$#,##0;($#,##0)",
					0x2b => '%1.2f',    //"#,##0.00;(#,##0.00)",
					0x2c => '$%1.2f',   //"$#,##0.00;($#,##0.00)",
					0x30 => '%1.0f');   //"##0.0E0";
				function Spreadsheet_Excel_Reader() {
					$this->_ole = new OLERead();
					$this->setUTFEncoder('iconv');
				}
				function setOutputEncoding($encoding) {
					$this->_defaultEncoding = $encoding;
				}
				function setUTFEncoder($encoder = 'iconv') {
					$this->_encoderFunction = '';

					if ($encoder == 'iconv') {
						$this->_encoderFunction = function_exists('iconv') ? 'iconv' : '';
					} elseif ($encoder == 'mb') {
						$this->_encoderFunction = function_exists('mb_convert_encoding') ?
												  'mb_convert_encoding' :
												  '';
					}
				}
				function setRowColOffset($iOffset) {
					$this->_rowoffset = $iOffset;
					$this->_coloffset = $iOffset;
				}
				function setDefaultFormat($sFormat) {
					$this->_defaultFormat = $sFormat;
				}
				function setColumnFormat($column, $sFormat) {
					$this->_columnsFormat[$column] = $sFormat;
				}
				function read($sFileName) {
					$res = $this->_ole->read($sFileName);
					if($res === false) {
						if($this->_ole->error == 1) {
							die('The filename ' . $sFileName . ' is not readable');
						}
					}
					$this->data = $this->_ole->getWorkBook();
					$this->_parse();
				}
				function _parse() {
					$pos = 0;

					$code = ord($this->data[$pos]) | ord($this->data[$pos+1])<<8;
					$length = ord($this->data[$pos+2]) | ord($this->data[$pos+3])<<8;

					$version = ord($this->data[$pos + 4]) | ord($this->data[$pos + 5])<<8;
					$substreamType = ord($this->data[$pos + 6]) | ord($this->data[$pos + 7])<<8;
					if (($version != SPREADSHEET_EXCEL_READER_BIFF8) &&
						($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
						return false;
					}

					if ($substreamType != SPREADSHEET_EXCEL_READER_WORKBOOKGLOBALS) {
						return false;
					}
					$pos += $length + 4;

					$code = ord($this->data[$pos]) | ord($this->data[$pos+1])<<8;
					$length = ord($this->data[$pos+2]) | ord($this->data[$pos+3])<<8;

					while ($code != SPREADSHEET_EXCEL_READER_TYPE_EOF) {
						switch ($code) {
							case SPREADSHEET_EXCEL_READER_TYPE_SST:
								 $spos = $pos + 4;
								 $limitpos = $spos + $length;
								 $uniqueStrings = $this->_GetInt4d($this->data, $spos+4);
															$spos += 8;
												   for ($i = 0; $i < $uniqueStrings; $i++) {
															if ($spos == $limitpos) {
															$opcode = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
															$conlength = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
																	if ($opcode != 0x3c) {
																			return -1;
																	}
															$spos += 4;
															$limitpos = $spos + $conlength;
															}
															$numChars = ord($this->data[$spos]) | (ord($this->data[$spos+1]) << 8);
															$spos += 2;
															$optionFlags = ord($this->data[$spos]);
															$spos++;
													$asciiEncoding = (($optionFlags & 0x01) == 0) ;
															$extendedString = ( ($optionFlags & 0x04) != 0);
															$richString = ( ($optionFlags & 0x08) != 0);

															if ($richString) {
																	$formattingRuns = ord($this->data[$spos]) | (ord($this->data[$spos+1]) << 8);
																	$spos += 2;
															}

															if ($extendedString) {
															  $extendedRunLength = $this->_GetInt4d($this->data, $spos);
															  $spos += 4;
															}
															$len = ($asciiEncoding)? $numChars : $numChars*2;
															if ($spos + $len < $limitpos) {
																			$retstr = substr($this->data, $spos, $len);
																			$spos += $len;
															} else {
																	$retstr = substr($this->data, $spos, $limitpos - $spos);
																	$bytesRead = $limitpos - $spos;
																	$charsLeft = $numChars - (($asciiEncoding) ? $bytesRead : ($bytesRead / 2));
																	$spos = $limitpos;

																	 while ($charsLeft > 0){
																			$opcode = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
																			$conlength = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
																					if ($opcode != 0x3c) {
																							return -1;
																					}
																			$spos += 4;
																			$limitpos = $spos + $conlength;
																			$option = ord($this->data[$spos]);
																			$spos += 1;
																			  if ($asciiEncoding && ($option == 0)) {
																							$len = min($charsLeft, $limitpos - $spos); // min($charsLeft, $conlength);
																				$retstr .= substr($this->data, $spos, $len);
																				$charsLeft -= $len;
																				$asciiEncoding = true;
																			  }elseif (!$asciiEncoding && ($option != 0)){
																							$len = min($charsLeft * 2, $limitpos - $spos); // min($charsLeft, $conlength);
																				$retstr .= substr($this->data, $spos, $len);
																				$charsLeft -= $len/2;
																				$asciiEncoding = false;
																			  }elseif (!$asciiEncoding && ($option == 0)) {
																							$len = min($charsLeft, $limitpos - $spos); 
																					for ($j = 0; $j < $len; $j++) {
																			 $retstr .= $this->data[$spos + $j].chr(0);
																			}
																		$charsLeft -= $len;
																			$asciiEncoding = false;
																			  }else{
																		$newstr = '';
																				for ($j = 0; $j < strlen($retstr); $j++) {
																				  $newstr = $retstr[$j].chr(0);
																				}
																				$retstr = $newstr;
																							$len = min($charsLeft * 2, $limitpos - $spos); 
																				$retstr .= substr($this->data, $spos, $len);
																				$charsLeft -= $len/2;
																				$asciiEncoding = false;
																			  }
																	  $spos += $len;

																	 }
															}
															$retstr = ($asciiEncoding) ? $retstr : $this->_encodeUTF16($retstr);

													if ($richString){
															  $spos += 4 * $formattingRuns;
															}
															if ($extendedString) {
															  $spos += $extendedRunLength;
															}
															$this->sst[]=$retstr;
												   }
								break;

							case SPREADSHEET_EXCEL_READER_TYPE_FILEPASS:
								return false;
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_NAME:
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_FORMAT:
									$indexCode = ord($this->data[$pos+4]) | ord($this->data[$pos+5]) << 8;

									if ($version == SPREADSHEET_EXCEL_READER_BIFF8) {
										$numchars = ord($this->data[$pos+6]) | ord($this->data[$pos+7]) << 8;
										if (ord($this->data[$pos+8]) == 0){
											$formatString = substr($this->data, $pos+9, $numchars);
										} else {
											$formatString = substr($this->data, $pos+9, $numchars*2);
										}
									} else {
										$numchars = ord($this->data[$pos+6]);
										$formatString = substr($this->data, $pos+7, $numchars*2);
									}

								$this->formatRecords[$indexCode] = $formatString;
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_XF:
									$indexCode = ord($this->data[$pos+6]) | ord($this->data[$pos+7]) << 8;
									if (array_key_exists($indexCode, $this->dateFormats)) {
										$this->formatRecords['xfrecords'][] = array(
												'type' => 'date',
												'format' => $this->dateFormats[$indexCode]
												);
									}elseif (array_key_exists($indexCode, $this->numberFormats)) {
										$this->formatRecords['xfrecords'][] = array(
												'type' => 'number',
												'format' => $this->numberFormats[$indexCode]
												);
									}else{
										$isdate = FALSE;
										if ($indexCode > 0){
											if (isset($this->formatRecords[$indexCode]))
												$formatstr = $this->formatRecords[$indexCode];
											if ($formatstr)
											if (preg_match("/[^hmsday\/\-:\s]/i", $formatstr) == 0) { // found day and time format
												$isdate = TRUE;
												$formatstr = str_replace('mm', 'i', $formatstr);
												$formatstr = str_replace('h', 'H', $formatstr);
											}
										}

										if ($isdate){
											$this->formatRecords['xfrecords'][] = array(
													'type' => 'date',
													'format' => $formatstr,
													);
										}else{
											$this->formatRecords['xfrecords'][] = array(
													'type' => 'other',
													'format' => '',
													'code' => $indexCode
													);
										}
									}
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_NINETEENFOUR:
								$this->nineteenFour = (ord($this->data[$pos+4]) == 1);
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_BOUNDSHEET:
									$rec_offset = $this->_GetInt4d($this->data, $pos+4);
									$rec_typeFlag = ord($this->data[$pos+8]);
									$rec_visibilityFlag = ord($this->data[$pos+9]);
									$rec_length = ord($this->data[$pos+10]);

									if ($version == SPREADSHEET_EXCEL_READER_BIFF8){
										$chartype =  ord($this->data[$pos+11]);
										if ($chartype == 0){
											$rec_name    = substr($this->data, $pos+12, $rec_length);
										} else {
											$rec_name    = $this->_encodeUTF16(substr($this->data, $pos+12, $rec_length*2));
										}
									}elseif ($version == SPREADSHEET_EXCEL_READER_BIFF7){
											$rec_name    = substr($this->data, $pos+11, $rec_length);
									}
								$this->boundsheets[] = array('name'=>$rec_name,
															 'offset'=>$rec_offset);

								break;

						}
						$pos += $length + 4;
						$code = ord($this->data[$pos]) | ord($this->data[$pos+1])<<8;
						$length = ord($this->data[$pos+2]) | ord($this->data[$pos+3])<<8;
					}

					foreach ($this->boundsheets as $key=>$val){
						$this->sn = $key;
						$this->_parsesheet($val['offset']);
					}
					return true;

				}
				function _parsesheet($spos)
				{
					$cont = true;
					// read BOF
					$code = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
					$length = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;

					$version = ord($this->data[$spos + 4]) | ord($this->data[$spos + 5])<<8;
					$substreamType = ord($this->data[$spos + 6]) | ord($this->data[$spos + 7])<<8;

					if (($version != SPREADSHEET_EXCEL_READER_BIFF8) && ($version != SPREADSHEET_EXCEL_READER_BIFF7)) {
						return -1;
					}

					if ($substreamType != SPREADSHEET_EXCEL_READER_WORKSHEET){
						return -2;
					}
					$spos += $length + 4;
					while($cont) {
						$lowcode = ord($this->data[$spos]);
						if ($lowcode == SPREADSHEET_EXCEL_READER_TYPE_EOF) break;
						$code = $lowcode | ord($this->data[$spos+1])<<8;
						$length = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
						$spos += 4;
						$this->sheets[$this->sn]['maxrow'] = $this->_rowoffset - 1;
						$this->sheets[$this->sn]['maxcol'] = $this->_coloffset - 1;
						unset($this->rectype);
						$this->multiplier = 1; // need for format with %
						switch ($code) {
							case SPREADSHEET_EXCEL_READER_TYPE_DIMENSION:
								if (!isset($this->numRows)) {
									if (($length == 10) ||  ($version == SPREADSHEET_EXCEL_READER_BIFF7)){
										$this->sheets[$this->sn]['numRows'] = ord($this->data[$spos+2]) | ord($this->data[$spos+3]) << 8;
										$this->sheets[$this->sn]['numCols'] = ord($this->data[$spos+6]) | ord($this->data[$spos+7]) << 8;
									} else {
										$this->sheets[$this->sn]['numRows'] = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
										$this->sheets[$this->sn]['numCols'] = ord($this->data[$spos+10]) | ord($this->data[$spos+11]) << 8;
									}
								}
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_MERGEDCELLS:
								$cellRanges = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								for ($i = 0; $i < $cellRanges; $i++) {
									$fr =  ord($this->data[$spos + 8*$i + 2]) | ord($this->data[$spos + 8*$i + 3])<<8;
									$lr =  ord($this->data[$spos + 8*$i + 4]) | ord($this->data[$spos + 8*$i + 5])<<8;
									$fc =  ord($this->data[$spos + 8*$i + 6]) | ord($this->data[$spos + 8*$i + 7])<<8;
									$lc =  ord($this->data[$spos + 8*$i + 8]) | ord($this->data[$spos + 8*$i + 9])<<8;
									if ($lr - $fr > 0) {
										$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['rowspan'] = $lr - $fr + 1;
									}
									if ($lc - $fc > 0) {
										$this->sheets[$this->sn]['cellsInfo'][$fr+1][$fc+1]['colspan'] = $lc - $fc + 1;
									}
								}
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_RK:
							case SPREADSHEET_EXCEL_READER_TYPE_RK2:
								$row = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$column = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								$rknum = $this->_GetInt4d($this->data, $spos + 6);
								$numValue = $this->_GetIEEE754($rknum);
								if ($this->isDate($spos)) {
									list($string, $raw) = $this->createDate($numValue);
								}else{
									$raw = $numValue;
									if (isset($this->_columnsFormat[$column + 1])){
											$this->curformat = $this->_columnsFormat[$column + 1];
									}
									$string = sprintf($this->curformat, $numValue * $this->multiplier);
								}
								$this->addcell($row, $column, $string, $raw);
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_LABELSST:
									$row        = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
									$column     = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
									$xfindex    = ord($this->data[$spos+4]) | ord($this->data[$spos+5])<<8;
									$index  = $this->_GetInt4d($this->data, $spos + 6);
									$this->addcell($row, $column, $this->sst[$index]);
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_MULRK:
								$row        = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$colFirst   = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								$colLast    = ord($this->data[$spos + $length - 2]) | ord($this->data[$spos + $length - 1])<<8;
								$columns    = $colLast - $colFirst + 1;
								$tmppos = $spos+4;
								for ($i = 0; $i < $columns; $i++) {
									$numValue = $this->_GetIEEE754($this->_GetInt4d($this->data, $tmppos + 2));
									if ($this->isDate($tmppos-4)) {
										list($string, $raw) = $this->createDate($numValue);
									}else{
										$raw = $numValue;
										if (isset($this->_columnsFormat[$colFirst + $i + 1])){
													$this->curformat = $this->_columnsFormat[$colFirst + $i + 1];
											}
										$string = sprintf($this->curformat, $numValue * $this->multiplier);
									}
								  $tmppos += 6;
								  $this->addcell($row, $colFirst + $i, $string, $raw);
								}
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_NUMBER:
								$row    = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$column = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								$tmp = unpack("ddouble", substr($this->data, $spos + 6, 8));
								if ($this->isDate($spos)) {
									list($string, $raw) = $this->createDate($tmp['double']);
								}else{
									if (isset($this->_columnsFormat[$column + 1])){
											$this->curformat = $this->_columnsFormat[$column + 1];
									}
									$raw = $this->createNumber($spos);
									$string = sprintf($this->curformat, $raw * $this->multiplier);
								}
								$this->addcell($row, $column, $string, $raw);
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_FORMULA:
							case SPREADSHEET_EXCEL_READER_TYPE_FORMULA2:
								$row    = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$column = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								if ((ord($this->data[$spos+6])==0) && (ord($this->data[$spos+12])==255) && (ord($this->data[$spos+13])==255)) {
								} elseif ((ord($this->data[$spos+6])==1) && (ord($this->data[$spos+12])==255) && (ord($this->data[$spos+13])==255)) {
								} elseif ((ord($this->data[$spos+6])==2) && (ord($this->data[$spos+12])==255) && (ord($this->data[$spos+13])==255)) {
								} elseif ((ord($this->data[$spos+6])==3) && (ord($this->data[$spos+12])==255) && (ord($this->data[$spos+13])==255)) {
								} else {
									$tmp = unpack("ddouble", substr($this->data, $spos + 6, 8));
									if ($this->isDate($spos)) {
										list($string, $raw) = $this->createDate($tmp['double']);
									}else{
										if (isset($this->_columnsFormat[$column + 1])){
												$this->curformat = $this->_columnsFormat[$column + 1];
										}
										$raw = $this->createNumber($spos);
										$string = sprintf($this->curformat, $raw * $this->multiplier);
									}
									$this->addcell($row, $column, $string, $raw);
								}
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_BOOLERR:
								$row    = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$column = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								$string = ord($this->data[$spos+6]);
								$this->addcell($row, $column, $string);
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_ROW:
							case SPREADSHEET_EXCEL_READER_TYPE_DBCELL:
							case SPREADSHEET_EXCEL_READER_TYPE_MULBLANK:
								break;
							case SPREADSHEET_EXCEL_READER_TYPE_LABEL:
								$row    = ord($this->data[$spos]) | ord($this->data[$spos+1])<<8;
								$column = ord($this->data[$spos+2]) | ord($this->data[$spos+3])<<8;
								$this->addcell($row, $column, substr($this->data, $spos + 8, ord($this->data[$spos + 6]) | ord($this->data[$spos + 7])<<8));
								break;

							case SPREADSHEET_EXCEL_READER_TYPE_EOF:
								$cont = false;
								break;
							default:
								break;

						}
						$spos += $length;
					}

					if (!isset($this->sheets[$this->sn]['numRows']))
						 $this->sheets[$this->sn]['numRows'] = $this->sheets[$this->sn]['maxrow'];
					if (!isset($this->sheets[$this->sn]['numCols']))
						 $this->sheets[$this->sn]['numCols'] = $this->sheets[$this->sn]['maxcol'];

				}
				function isDate($spos)
				{
					$xfindex = ord($this->data[$spos+4]) | ord($this->data[$spos+5]) << 8;
					if ($this->formatRecords['xfrecords'][$xfindex]['type'] == 'date') {
						$this->curformat = $this->formatRecords['xfrecords'][$xfindex]['format'];
						$this->rectype = 'date';
						return true;
					} else {
						if ($this->formatRecords['xfrecords'][$xfindex]['type'] == 'number') {
							$this->curformat = $this->formatRecords['xfrecords'][$xfindex]['format'];
							$this->rectype = 'number';
							if (($xfindex == 0x9) || ($xfindex == 0xa)){
								$this->multiplier = 100;
							}
						}else{
							$this->curformat = $this->_defaultFormat;
							$this->rectype = 'unknown';
						}
						return false;
					}
				}
				function createDate($numValue)
				{
					if ($numValue > 1) {
						$utcDays = $numValue - ($this->nineteenFour ? SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS1904 : SPREADSHEET_EXCEL_READER_UTCOFFSETDAYS);
						$utcValue = round(($utcDays+1) * SPREADSHEET_EXCEL_READER_MSINADAY);
						$string = date ($this->curformat, $utcValue);
						$raw = $utcValue;
					} else {
						$raw = $numValue;
						$hours = floor($numValue * 24);
						$mins = floor($numValue * 24 * 60) - $hours * 60;
						$secs = floor($numValue * SPREADSHEET_EXCEL_READER_MSINADAY) - $hours * 60 * 60 - $mins * 60;
						$string = date ($this->curformat, mktime($hours, $mins, $secs));
					}

					return array($string, $raw);
				}

				function createNumber($spos)
				{
					$rknumhigh = $this->_GetInt4d($this->data, $spos + 10);
					$rknumlow = $this->_GetInt4d($this->data, $spos + 6);
					$sign = ($rknumhigh & 0x80000000) >> 31;
					$exp =  ($rknumhigh & 0x7ff00000) >> 20;
					$mantissa = (0x100000 | ($rknumhigh & 0x000fffff));
					$mantissalow1 = ($rknumlow & 0x80000000) >> 31;
					$mantissalow2 = ($rknumlow & 0x7fffffff);
					$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
					if ($mantissalow1 != 0) $value += 1 / pow (2 , (21 - ($exp - 1023)));
					$value += $mantissalow2 / pow (2 , (52 - ($exp - 1023)));

					if ($sign) {$value = -1 * $value;}
					return  $value;
				}

				function addcell($row, $col, $string, $raw = '')
				{
					$this->sheets[$this->sn]['maxrow'] = max($this->sheets[$this->sn]['maxrow'], $row + $this->_rowoffset);
					$this->sheets[$this->sn]['maxcol'] = max($this->sheets[$this->sn]['maxcol'], $col + $this->_coloffset);
					$this->sheets[$this->sn]['cells'][$row + $this->_rowoffset][$col + $this->_coloffset] = $string;
					if ($raw)
						$this->sheets[$this->sn]['cellsInfo'][$row + $this->_rowoffset][$col + $this->_coloffset]['raw'] = $raw;
					if (isset($this->rectype))
						$this->sheets[$this->sn]['cellsInfo'][$row + $this->_rowoffset][$col + $this->_coloffset]['type'] = $this->rectype;

				}


				function _GetIEEE754($rknum)
				{
					if (($rknum & 0x02) != 0) {
							$value = $rknum >> 2;
					} else {
					 $sign = ($rknum & 0x80000000) >> 31;
					$exp = ($rknum & 0x7ff00000) >> 20;
					$mantissa = (0x100000 | ($rknum & 0x000ffffc));
					$value = $mantissa / pow( 2 , (20- ($exp - 1023)));
					if ($sign) {$value = -1 * $value;}
					}

					if (($rknum & 0x01) != 0) {
						$value /= 100;
					}
					return $value;
				}

				function _encodeUTF16($string)
				{
					$result = $string;
					if ($this->_defaultEncoding){
						switch ($this->_encoderFunction){
							case 'iconv' :     $result = iconv('UTF-16LE', $this->_defaultEncoding, $string);
											break;
							case 'mb_convert_encoding' :     $result = mb_convert_encoding($string, $this->_defaultEncoding, 'UTF-16LE' );
											break;
						}
					}
					return $result;
				}

				function _GetInt4d($data, $pos)
				{
					$value = ord($data[$pos]) | (ord($data[$pos+1]) << 8) | (ord($data[$pos+2]) << 16) | (ord($data[$pos+3]) << 24);
					if ($value>=4294967294)
					{
						$value=-2;
					}
					return $value;
				}

			}
			
			$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
			$uploadfile = $uploaddir . basename($_FILES['xlsfile']['name']);
			if (move_uploaded_file($_FILES['xlsfile']['tmp_name'], $uploadfile)) {
			} else {
				echo "ERROR\n";
			}
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$data->read($uploadfile);
			error_reporting(E_ALL ^ E_NOTICE);

			//массив из параметров товаров
					$params_code_array = array();
					$params_ids_array = array();
					$Db->query="SELECT `code`,`id_goods` FROM `mod_catalog` WHERE `code`!=''";
					$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) 
					{
						while($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
						{
							$params_code_array[$lRes["id_goods"]]=$lRes["code"];
							$params_ids_array[$lRes["id_goods"]]=$lRes["id_goods"];
						}
					}

			$num_cat = 0;
			$query_insert = '';
			$query_update = '';
			$query_update2 = '';
			$query_update3 = '';
			$query_update4 = '';
			$query_update5 = '';
			$query_update6 = '';
			
			$query_params_update = '';
			$query_act = '';
						
			for ($i = 0; $i <= $data->sheets[0]['numRows']-1; $i++) 
			{
				//пытаемся найти код текущего товара среди всех кодов товаров из таблицы с параметрами товаров
				if ($curr_id=array_search($data->sheets[0]['cells'][$i+1][1],$params_code_array))
				{
					$price=$data->sheets[0]['cells'][$i+1][4];
					$parameter=$data->sheets[0]['cells'][$i+1][3];
					$id_good=$params_ids_array[$curr_id];
					
					$query_price_update.=" WHEN `code`='".$data->sheets[0]['cells'][$i+1][1]."' THEN '".$price."'";
					$query_params_update.=" WHEN `code`='".$data->sheets[0]['cells'][$i+1][1]."' THEN '".$parameter."'";
					$query_act.= " WHEN `id_goods`='".$id_good."' THEN '1'";						
					$query_avail.= " WHEN `id_goods`='".$id_good."' THEN '1'";						
					
					//$price = str_replace(",",".",$data->sheets[0]['cells'][$i][3]);
					//$query_update.= " WHEN `barcode`='".$data->sheets[0]['cells'][$i+1][2]."' THEN '".$price."'";
					//$query_update3.= " WHEN `barcode`='".$data->sheets[0]['cells'][$i+1][2]."' THEN '".$data->sheets[0]['cells'][$i+1][4]."'";
					//$query_update2.= " WHEN `barcode`='".$data->sheets[0]['cells'][$i+1][2]."' THEN '1'";
				}
			}
			
			if (@$_POST["act_off"]) mysql_query("UPDATE `mod_catalog` SET `act` = '0'");
			mysql_query("UPDATE `mod_catalog` SET `avail` = '0'");
			
			if (!empty($query_price_update)) mysql_query("UPDATE `mod_catalog` SET `price` = CASE ".$query_price_update." ELSE `price` END");
			if (!empty($query_params_update)) mysql_query("UPDATE `mod_catalog` SET `units` = CASE ".$query_params_update." ELSE `units` END");
			if (!empty($query_act)) mysql_query("UPDATE `mod_catalog` SET `act` = CASE ".$query_act." ELSE `act` END");
			if (!empty($query_avail)) mysql_query("UPDATE `mod_catalog` SET `avail` = CASE ".$query_avail." ELSE `avail` END");
			
			//if (!empty($query_insert)) mysql_query("INSERT INTO `mod_catalog` (`name_goods`, `barcode`, `price`, `act`, `cat`, `anchor_goods`, `stock`) VALUES ".substr($query_insert, 0, -1));
			//if (!empty($query_update)) mysql_query("UPDATE `mod_catalog` SET `price` = CASE ".$query_update." ELSE `price` END");
			//if (!empty($query_update2)) mysql_query("UPDATE `mod_catalog` SET `act` = CASE ".$query_update2." ELSE `act` END");
			//if (!empty($query_update3)) mysql_query("UPDATE `mod_catalog` SET `stock` = CASE ".$query_update3." ELSE `stock` END");

			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=catalog&action=list'></head></html>");
			unlink($uploadfile);
		} else {
		?>
		<b>Инструкция:</b> <br />
		<br />
		В списке товаров не должно быть лишних строк (логотипов, заголовков, телефонов и пр.). <br />
		Внимание! Если товар, присутствующий на сайте, отсутствует в загружаемом прайс-листе, то он получит статус "Нет в наличии".
		<br />
		Столбцы должны быть в следующем порядке - <b>Артикул | Название | Единицы измерения | Цена </b>(цена - только цифры, без руб.; параметр - указывать ед. измерения (кг., л., уп.)). <br />
		<br />
		<form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
		Файл XLS: <input type="file" name="xlsfile"/>
		<br /><input name='act_off' type='checkbox' value='on' /> Снять активность со всех товаров перед обновлением<br />
		<input type="submit" name="submit" value="Загрузить">
		</form>
		<?php
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
				$content_mod.= '<p><input class="check" name="'.$lRes[option].'" type="'.$lRes[type].'"'.$chek.$val;
				if ($lRes[type]=="text") $content_mod.= ' size="50"';
				$content_mod.= '  /> '.$lRes[name].'</p>';
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
// необходимые функции для этого модуля
	
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
	
	function forech_pages($data, $temp, $slash)
{ 
$rank = sizeof($data, $num);
if ($temp==0) { 
	echo '<form method="post" action="index.php?mod=catalog&action=pereschet_cat" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Название</td><td width="60"></td><td width="80" class="nobg"><div class="conf"><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
}
//echo '<ul>';
foreach ($data as $k=>$v)
    {
		//$down = "<a href='index.php?mod=catalog&action=list_cat&down_cat=".$v['id_cat']."' title='Развернуть'><img src='img/icons/down_arrow_no.png' class='pnghack' align='middle' border='0' /></a>"; 
		
		//if ($v['in_menu']!=0) $in_menu = "<img src='images/actyes.png' class='pnghack' alt='Показывается в меню' title='Показывается в меню' />"; else $in_menu = "<img src='images/actno.png' class='pnghack' alt='Непоказывается в меню' title='Не показывается в меню' />";
       // if ($v['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Страница активна' title='Страница активна' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Страница не активна' title='Страница не активна' />";
		
		echo '<tr><td class="pages">'.$slash.'<a href="index.php?mod=catalog&action=list&cat='.$v["id_cat"].'" title="Показать товары"><img src="img/icons/database_server.png" align="middle" border="0" /></a>&nbsp;&nbsp;&nbsp;<a href="index.php?mod=catalog&action=edit_cat&id='.$v["id_cat"].'">'.$v['name_cat'].'</a></td>';
		
		echo "</td><td class='pages'></td>
		<td class='pages'><div class='conf'>
		$down&nbsp;&nbsp;";
		echo "<input type='hidden' value='0' name='act[".$v['id_cat']."]' />";
		if ($v['act']!=0) echo "<input type='checkbox' value='1' name='act[".$v['id_cat']."]' class='checkboxact' checked='checked' />"; else echo "<input type='checkbox' value='1' name='act[".$v['id_cat']."]' class='checkboxact' />";
		echo "<input type='checkbox' value='1' name='delete[".$v['id_cat']."]' class='checkbox'  />
		</div>";
      		if (isset($v['childs'])) forech_pages($v['childs'], 1, $slash."&nbsp;&nbsp;&nbsp;");
		echo "
		</td></tr>";
    }
//echo "</ul>";
if ($temp==0) 
	{
	echo '<tr><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
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
?>