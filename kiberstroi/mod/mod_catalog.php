<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
		$filter = new filter;
		if (what_ras($param[2])=="html") $page_name = substr($filter->html_filter(@$param[2]), 0, -5); else $page_str = $filter->html_filter(@$param[2]);
		$page = $filter->html_filter(@$param[1]);
		$page_vars = explode("-",$page);
		$current_page_id = $page_vars[0];
		$page_var = $page_vars[1];
		$page_var = explode('?',$page_vars[1]);
		$page_var = $page_var[0];
		$page_active='katalog';
		$is_catalog=true;
		
		$show_fast_order=true;
		
		//$content.='<h1>Каталог строительных и отделочных материалов</h1>';
		
if (!$page_name){ // если находимся не в товаре
	if (!@$page_var) 
	{ // если главная каталога
		
		$is_catalog=false;
		$content.='<div class="breadcrombs"><a href="/">Главная</a> / Каталог</div>
		  <h1>Каталог</h1>';
		
				$Db->query="SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent`,`img_cat` FROM `mod_catalog_cat` WHERE act='1' AND parent='0' AND img_cat!='' ORDER BY rank";
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {
					$catalog = '<ul id="services">';
					while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					{
						$catalog.= '<li class = "cat_'.$lRes["id_cat"].'">	
							<div style="background: url(\'/upload/cat/'.$lRes["img_cat"].'.png\') no-repeat 50% 0;"></div>
							<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/"><span>'.$lRes["name_cat"].'</span></a>
						</li>'; 
					}
					$content.=$catalog;
				}
	}	
	else // если не главная каталога отображаем категорию
	{	

		$Db->query="SELECT * FROM `mod_catalog_cat` WHERE `id_cat`='".$current_page_id."' LIMIT 1";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$content.='<div class="breadcrombs"><a href="/">Главная</a> / <a href="/catalog/">Каталог</a> / '.$lRes["name_cat"].'</div>';
		  
		$name_cat  = $lRes[name_cat];
		$currentcat = cat($lRes[id_cat]);
		$current_cat_id = $lRes['id_cat'];
		foreach ($currentcat as $keys=>$values) foreach ($values as $kk=>$vv) $currents[]= $values[0];
		$currents = array_unique($currents);
		
		$title = ($lRes['title']=='') ? 'Каталог - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
		$keys = ($lRes['keys']=='') ? 'каталог,'.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
		$meta = ($lRes['meta']=='') ? 'Каталог - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];		

		$id_cat = $lRes['id_cat'];
		$text_cat = str_replace("../","/",stripslashes($lRes['text_cat']));

		$content.= '<h1>'.$name_cat.'</h1>';


		// подкатегории
		$content.= '<div class="list_cat">';
		$Db->query="SELECT `img_cat`,`anchor_cat`,`parent`,`name_cat`,`id_cat`
						FROM `mod_catalog_cat` 
						WHERE `act`='1' AND `parent`='".$id_cat."' AND `img_cat`!='' ORDER BY rank";
		$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	$content.= "<div class='cat_main'><a href='/catalog/".$lRes[id_cat]."-".$lRes[anchor_cat]."/'><img src='/upload/cat/".$lRes[img_cat].".png' /></a><br /><a href='/catalog/".$lRes[id_cat]."-".$lRes[anchor_cat]."/'>".$lRes[name_cat]."</a></div>";
		$content.= 	'</div>';	
		$order_by = "price";
			
			
// ============================== ФИЛЬТРАЦИЯ

				
			//находим все параметры для вывода фильтра
			$Db->query="SELECT *
						FROM filter_goods 
						LEFT JOIN mod_catalog ON (filter_goods.goods_id=mod_catalog.id_goods)
						WHERE cat='".$current_page_id."' AND mod_catalog.act='1' ORDER BY price";
			//if (@$_POST or @$_GET["add"] && !empty($goods_query)) $Db->query.=$goods_query;
			$Db->query();
			
			if (mysql_num_rows(($Db->lQueryResult))>0)
				{
					$show_filter = 1;
					$temp1 = 1;
					
					$filter = array();
					while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $filter[$lRes["filter_goods_rel"]][]=$lRes["params_rel"]; 
					
				}
				else $show_filter = 0;

				// $filter_block собирает весь фильтр и выводит его сверху на страницах каталога
							
				$count=0;
				
				foreach($filter as $key=>$value)
				{
					$Db->query="SELECT * FROM filter_params
										LEFT JOIN filter ON (filter.id_filter=filter_params.filter_rel)
										WHERE filter.id_filter='".$key."'";
					$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) { 
						$lRes=mysql_fetch_assoc($Db->lQueryResult);
							
						$count++;						
						$filter_block.= '<div class="tags" id="tags_'.$lRes['id_filter'].'">'.$lRes['name_filter'].'
						';					
						do 
						{	
							if (@$_POST[$lRes['id_filter']]==$lRes["id_params"]) 
								$chek = ' selected'; 
							else $chek = '';
							if (in_array($lRes["id_params"],$filter[$key])) {
								$count++;
								$filter_block.= '<a data-id="'.$lRes['id_params'].'" id="params_'.$lRes['id_params'].'" data-cat="'.$lRes['id_filter'].'">'.$lRes['name_params'].'</a>
								';
							}
						}
						while ($lRes=mysql_fetch_assoc($Db->lQueryResult));
						$filter_block.= '</div>';
					}
				}
				
				
// ============================================

			$content.=$filter_block."<div style='display:none;' data-id='".$current_page_id."' id='this_page'></div>";
		
			$Db->query="SELECT mod_catalog.*, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
						FROM `mod_catalog` 
						LEFT JOIN mod_catalog_cat ON (mod_catalog_cat.id_cat=mod_catalog.cat) WHERE cat='".$current_page_id."'";
			if (@$_POST or @$_GET["add"] && !empty($goods_query)) $Db->query.=$goods_query.$param_query;
			$Db->query.=" AND mod_catalog.act='1' ORDER BY ".$order_by."";
			$Db->query();

			$filter = new filter;
			$view_goods = $filter->html_filter(@$_GET["view"]);
			$all = mysql_num_rows($Db->lQueryResult);
			if ($its_no_goods==1) $content.= '';			
			elseif (($all>0)&&(!$goods_not_found))
			{			
				$content.= '<div class="products_list">
							<ul>';
				while ($lRes=mysql_fetch_array($Db->lQueryResult)) { 
					if ($lRes["img_good"]!='') $img=$lRes["img_good"]; else $img="empty";
					
					$content.= '<li>
						<div class="product_image"><img src="/upload/goods/sm'.$img.'.jpg" alt=""></div>
						<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html" class="product_name">'.$lRes["name_goods"].'</a>
						<div class="product_text">'.stripslashes($lRes["text_teh"]).'</div>
						<div class="product_price">'.$lRes["price"].' руб</div>
						<a href="#" class="button_buy" data-id="good-'.$lRes["id_goods"].'-'.$lRes["price"].'">в корзину</a>
						
					</li>';
				}
				$content.='</ul>';
				if (@$_GET or !empty($_POST)) {	
				
						if (@$_GET["add"] or !empty($_POST)) $bu.= '?add='.$export_filter.'&filter_id='.$export_filter_id;
						
						if (@$_GET["sort_price_up"]) {if (@$_GET["add"] or !empty($_POST)) $bu.= '&sort_price_up=on'; else $bu.= '?sort_price_up=on';}
						if (@$_GET["sort_name_down"]) {if (@$_GET["add"] or !empty($_POST)) $bu.= '&sort_name_down=on'; else $bu.= '?sort_name_down=on';}
						if (@$_GET["sort_name_up"]) {if (@$_GET["add"] or !empty($_POST)) $bu.= '&sort_name_up=on'; else $bu.= '?sort_name_up=on';}
						if (@$_GET["sort_name_down"]) {if (@$_GET["add"] or !empty($_POST)) $bu.= '&sort_name_down=on'; else $bu.= '?sort_name_down=on';}
						if (@$_GET["view"]) {if (@$_GET["add"] or @$_GET["sort_price_up"] or @$_GET["sort_name_down"] or @$_GET["sort_name_up"] or !empty($_POST)) $bu.= '&view='.$_GET["view"]; else $bu.= '?view='.$_GET["view"];}
					}
					else $bu = ''; 
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<li><a href=/catalog/'.$id_cat.'-'.$page_var.'/'.$i.'/'.$bu.'>['.$i.']</a><li>'; else $navi.= '<li><span>'.$i.'</span></li>';
				}
				if ($total > 1)	$navigation='<ul class="pagination">'.$navi.'</ul>';
				if (empty($param_query)) $content.= $navigation;
			}
			$content.=$text_cat;
	}

}
else // если находимся в товаре
{
	$page_active='in_good';
	$current_good_id=explode('-',$page_name);
	$current_good_id=$current_good_id['0'];
	$Db->query="SELECT mod_catalog.*, mod_catalog_cat.anchor_cat, mod_catalog_cat.name_cat FROM `mod_catalog` LEFT JOIN `mod_catalog_cat` ON (mod_catalog.cat=mod_catalog_cat.id_cat) WHERE `id_goods`='".$current_good_id."' AND mod_catalog.act='1' AND mod_catalog.cat='".$current_page_id."' LIMIT 1";
	$Db->query();
	
	if (mysql_num_rows($Db->lQueryResult)>0) {
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		//$content = cat_print($lRes[cat]).$lRes['name_goods']."</div>";
		
		$currentcats = cat($lRes[cat]);
		foreach ($currentcats as $keys=>$values) foreach ($values as $kk=>$vv) $currents[]= $values[0];
		$currents = array_unique($currents);
		$id = $lRes['id_goods'];
		$cat_id = $lRes['cat'];
		$current_cat_id = $cat_id;
		$anchor_cat = $lRes['anchor_cat'];
		$anchor_good = $lRes['anchor_goods'];
		$name = $lRes['name_goods'];
		
		$title = ($lRes['title']=='') ? $name.' - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
		$keys = ($lRes['keys']=='') ? $name.','.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
		$meta = ($lRes['meta']=='') ? $name.' - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];
		
		$price_min = $lRes['price'];
		$code = $lRes['code'];
		$name_cat = $lRes['name_cat'];
		$avail_flag=$lRes['avail'];
		
		$text_teh=str_replace("../","/",stripslashes($lRes['text_teh']));
		$text_goods=str_replace("../","/",stripslashes($lRes['text_goods']));
		$avail_array = array(1=>"есть на складе", 0=>"отсутствует");
		if (empty($lRes[img_good]) or !file_exists($_SERVER['DOCUMENT_ROOT']."/upload/goods/bg".$lRes[img_good].".jpg")) $img = '<img src="/upload/goods/smempty.jpg" alt="Нет фото" class="product_preview" width="300" />'; 
		else $img = '<a href="/upload/goods/bg'.$lRes[img_good].'.jpg" rel="group" class="in_goods_img_a"><img src="/upload/goods/bg'.$lRes[img_good].'.jpg" alt="'.$name.'" border="0" width="300" /></a>';
			$Db->query="SELECT `source` FROM `mod_file` WHERE `good`='".$lRes['id_goods']."' ORDER BY `id_file`";
			$Db->query();
			$dop = "";
			if (mysql_num_rows($Db->lQueryResult)>0) {
				while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $dop.= "<a rel='group' href='/upload/dop_goods/bg".$lRes[source].".jpg' class='in_goods_img_a its_dop_img'><img src='/upload/dop_goods/sm".$lRes[source].".jpg' border='0' width='50' /></a>";
			}	
	//	$content.='<h1>'.$name_cat.'</h1>';
	

		$content.='
		<div class="breadcrombs"><a href="/">Главная</a> / <a href="/catalog/">Каталог</a> / <a href="/catalog/'.$cat_id.'-'.$anchor_cat.'">'.$name_cat.'</a> / '.$name.'</div>
		<h1>'.$name.'</h1>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="320" valign="top">'.$img.'<br />'.$dop.'</td>
			<td valign="top">
			
			<input type="submit" value="купить" class="button_buy" style="position:relative; float:right; top:10px;" title="в корзину" data-id="good-'.$id.'-'.$lRes["price"].'" />
			<span class="price">'.$price_min.' руб.</span>
			'.$text_teh.'</td>
		  </tr>
		</table>
		<br />
			<h1>Описание</h1>
				<div class="text">
					'.$text_goods.'
				</div>';
			
			
			$Db->query="SELECT mod_catalog.*, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
						FROM `mod_catalog` 
						LEFT JOIN mod_catalog_cat ON (mod_catalog_cat.id_cat=mod_catalog.cat) WHERE mod_catalog.cat='".$cat_id."' AND `id_goods`!='".$id."' AND mod_catalog.act='1'";
			$Db->query();

			if (mysql_num_rows($Db->lQueryResult)>0)
			{			
					$content.= '<h2>Из этой же категории</h2>
					<div class="products_list">
							<ul>';
				while ($lRes=mysql_fetch_array($Db->lQueryResult)) { 
					if ($lRes["img_good"]!='') $img=$lRes["img_good"]; else $img="empty";
					
					$content.= '<li>
						<div class="product_image"><img src="/upload/goods/sm'.$img.'.jpg" alt=""></div>
						<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html" class="product_name">'.$lRes["name_goods"].'</a><div class="product_text">'.stripslashes($lRes["text_teh"]).'</div>
						<div class="product_price">'.$lRes["price"].' руб</div>
						<a href="#" class="button_buy" data-id="good-'.$lRes["id_goods"].'-'.$lRes["price"].'">в корзину</a>
						
					</li>';
				}
					
					$content.= '</ul>
					</div>';
			}

	
	
	}
	else
	{
		header('HTTP/1.0 404 not found');
		$content = "<h1>Ошибка.</h1><p>Страница не найдена.</p>";
	}

}

		$content.='</div>';
include("inc/header.php");
echo $content; 
include("inc/footer.php");
?>