<? 
		$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
		$Db->query="SELECT mod_catalog.*, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
						FROM `mod_catalog` 
						LEFT JOIN mod_catalog_cat ON (mod_catalog_cat.id_cat=mod_catalog.cat) WHERE mod_catalog.brand='1' AND mod_catalog.act='1' ORDER BY price"; 
			$Db->query();

			$filter = new filter;
			$view_goods = $filter->html_filter(@$_GET["view"]);
			
			if (mysql_num_rows($Db->lQueryResult)>0)
			{		
				$content.= '<div class="products_list">
							<ul>';
				while ($lRes=mysql_fetch_array($Db->lQueryResult)) { 
					if ($lRes["img_good"]!='') $img=$lRes["img_good"]; else $img="empty";
					
					$content.= '<li>
						<div class="product_image"><img src="/upload/goods/sm'.$img.'.jpg" alt=""></div>
						<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html" class="product_name">'.$lRes["name_goods"].'</a><div class="product_text">'.stripslashes($lRes["text_teh"]).'</div>
						<div class="product_price" style="color: red;">'.$lRes["price"].' руб</div>
						<a href="#" class="button_buy" data-id="good-'.$lRes["id_goods"].'-'.$lRes["price"].'">в корзину</a>
						
					</li>';
				}
				$content.='</ul>';
			}
?>