<? 

$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
// ��������� ��������� get �������
	$content = "";
	$page_active='search';
	$filter = new filter;
	$query = $filter->html_filter(substr($_POST["query"], 0, 30)); 
	
	$content.= '<div class="breadcrombs"><a href="/">�������</a> / �����</div>';
	
		if (empty($query) or strlen($query) < 3) $content.= "<h1>����� �� �����</h1>��������� ������ �� ������ ���� �� ����� 3 ��������.";
		else
		{
				$links = "";
				$words = explode(" ", $query);
				$words = Clear_array($words);

				$Db->query="SELECT * FROM `mod_catalog` LEFT JOIN `mod_catalog_cat` ON (mod_catalog.cat=mod_catalog_cat.id_cat) WHERE mod_catalog.act='1' AND (";
				foreach ($words as $value)
				{
				// ��� ������� ����� �������� ��� �����������-���������������� ����� =)
				if (strlen($value)>=4) $value = substr($value, 0, -1);
				// ��������� � $sql-������
				$Db->query.='name_goods LIKE (\'%'.$value.'%\') or '; 
				}
				// ������� ������ "or" � ����� ������
				$Db->query = substr($Db->query, 0, -4);
				$Db->query.=')';
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0)
				{
					$all = mysql_num_rows($Db->lQueryResult);
					$links.= '<div class="products_list">
							<ul>';
					while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					{
						if ($lRes["img_good"]!='') $img=$lRes["img_good"]; else $img="empty";
						$links.= '<li>
						<div class="product_image"><img src="/upload/goods/sm'.$img.'.jpg" alt=""></div>
						<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html" class="product_name">'.$lRes["name_goods"].'</a><div class="product_text">'.stripslashes($lRes["text_teh"]).'</div>
						<div class="product_price">'.$lRes["price"].' ���</div>
						<a href="#" class="button_buy" data-id="good-'.$lRes["id_goods"].'-'.$lRes["price"].'">� �������</a>
						
					</li>';
						
					}
					$links.= '</ul></div>';
					
					
				}				
					
			if (empty($links))
				$content.= "<h1>����� �� �����</h1>�� ������ ������� ������ �� �������.";
			else
				//$content.='�� ������ ������� �������:<br/>';
				$content.= $links;
				
			$Db->query="INSERT INTO `mod_search` VALUES('','".$query."',now())";
			$Db->query();
		}
	
include("inc/header.php");
echo $content;
include("inc/footer.php");
?>