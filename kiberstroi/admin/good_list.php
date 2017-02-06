<? 
require_once("db.php");
require_once("../mod/mod_func.php");
$Db=new Db ($DBServer,$DBLogin,$DBPassword,$DBName);
$Db->connect();
mysql_query("SET NAMES 'cp1251' ");

$filter = new filter; 
$cat = $filter->html_filter($_GET["cat_id"]);
$page = $filter->html_filter($_GET["page"]);

$content_mod = "<p class='add_green'><img src='images/plus_green.png' border='0' class='pnghack' align='absmiddle' /><a href='index.php?mod=catalog&action=edit&id=new&cat=".$cat."' title='Добавить товар в эту категорию'>Добавить товар в эту категорию</a></p>";
  	// запросы для постраничной навигации
			$num = 20; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_goods) FROM mod_catalog WHERE `cat`='".$cat."'"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_goods)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$a = substr($posts, strlen($posts)-1, 1); 
			$b = substr($posts, strlen($posts)-2, 2);
			// склоняем кол-во
			if ($a==0 or ($a>=5 and $a<=9) or ($b>=11 and $b<=14)) $posts_print = $posts.' товаров'; 
			if ($a==1 and $b!=11) $posts_print = $posts.' товар'; 
			if ($a>=2 and $a<=4 and $b!=12 and $b!=13 and $b!=14) $posts_print = $posts.' товара'; 
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			
			$Db->query="SELECT mod_catalog.id_goods,mod_catalog.name_goods,mod_catalog.act,mod_catalog.img_good,mod_catalog_cat.name_cat, mod_catalog.cat,mod_catalog_cat.anchor_cat,mod_catalog.anchor_goods,mod_catalog.code,mod_catalog.price
						FROM `mod_catalog`
						LEFT JOIN `mod_catalog_cat` ON (mod_catalog.cat=mod_catalog_cat.id_cat)
						WHERE  mod_catalog.cat='".$cat."'
						ORDER BY `id_goods` DESC 
						LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod.= '<h4>Всего: '.$posts_print.'</h4><table width="100%" class="one_gg" border="0" cellspacing="0" cellpadding="0">';
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Показывается' title='Показывается' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не показывается' title='Не показывается' />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					if (empty($lRes["name_goods"])) $lRes["name_goods"] = "-----------------";
					if (empty($lRes[img_good])) $img = '<img src="images/empty.jpg" alt="Нет фото" class="imgingood" />'; else $img = '<img src="/upload/goods/sm'.$lRes[img_good].'.jpg" alt="'.$lRes[img_good].'" class="imgingood" />';
					$content_mod.= '<tr class="one_news">
								<td width="25"><a href="/catalog/'.$lRes["cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["anchor_goods"].'.html" title="Посмотреть на сайте" target="_blank"><img src="images/insite.png" class="pnghack" border="0" width="20" /></a></td>
								<td width="60">'.$img.'</td>
								<td><a href="index.php?mod=catalog&action=edit&id='.$lRes["id_goods"].$pagestr.'">'.$lRes["name_goods"].'</a></td>
								<td width="60">'.$act.'

								<a href="#" onclick="Delete_good('.$lRes["id_goods"].','.$lRes["cat"].')" title="Удалить товар"><img src="images/del.png" class="pnghack" border="0" /></a>
								</td>
								</tr>';
								$num++;
				}
for ($i=1; $i<=$total; $i++) { 
	if ($page!=$i) 
	{
		$navi.='<a onclick="newContent(\'good_list.php?cat_id='.$cat.'&page='.$i.'\',\'goods\')" href="#">'.$i.'</a> | ';
	}
	else 
	{
		$navi.= '<b>'.$i.'</b> |';
	}
}
				if ($total > 1)	$navigation="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";
				$content_mod.= "</table>".$navigation;
			}
			else
			{
				$content_mod.= "<br />Товаров нет.";
			}
			
echo $content_mod;
?>