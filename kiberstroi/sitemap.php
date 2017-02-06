<? header('Content-type: text/xml');
include($_SERVER['DOCUMENT_ROOT']."/admin/db.php");
include($_SERVER['DOCUMENT_ROOT']."/mod/mod_func.php");
include($_SERVER['DOCUMENT_ROOT']."/mod/mod_config.php");

$site_url='http://kiberstroi.ru';

function sub_pages_xml($data, $num)
{ 
foreach ($data as $k=>$v)
    {
		if ($v["redirect"]!="#")
		{
			$date = date("Y-m-d", strtotime($v["edit_date"]));
			if (empty($v["redirect"])) $link = "/".$v["anchor"].".html"; else $link = $v["redirect"];
			echo '<url>
	<loc>'.$site_url.''.$link.'</loc>
	<lastmod>'.$date.'</lastmod>
	<changefreq>daily</changefreq>
</url>
';
		}

        if (isset($v['childs'])) echo sub_pages_xml($v['childs'], 1);
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';

$date = '';
		$Db->query="SELECT redirect, edit_date, anchor, parent, id_content FROM `mod_content` WHERE `act`='1' AND `in_menu`='1' ORDER BY `parent`,`rank`";
		$Db->query();
		if (mysql_num_rows($Db->lQueryResult)>0) 
		{
			while($lRes = mysql_fetch_assoc($Db->lQueryResult)) $data1[$lRes['parent']][] = $lRes;
			    $data1 = getTree($data1, 0);
			    sub_pages_xml($data1, 0);
		}	
	
		
		$cat_array=array();
		$Db->query="SELECT id_cat,anchor_cat FROM `mod_catalog_cat` WHERE `act`='1' ORDER BY `id_cat`";
		$Db->query();
		if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	
		{
			$cat_array[$lRes["id_cat"]]=$lRes["anchor_cat"];
			echo '<url>
	<loc>'.$site_url.'/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/</loc>
</url>
';
		}
		
		$Db->query="SELECT mod_catalog.cat,mod_catalog.anchor_goods,mod_catalog.edit_date,mod_catalog_cat.anchor_cat,mod_catalog.id_goods FROM `mod_catalog` LEFT JOIN `mod_catalog_cat` ON (mod_catalog.cat=mod_catalog_cat.id_cat) WHERE mod_catalog.act='1' AND mod_catalog.cat!='0' ORDER BY mod_catalog.edit_date DESC";
		$Db->query();
		
		if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	
		{					
			$date = date("Y-m-d", strtotime($lRes["edit_date"]));
			echo '<url>
	<loc>'.$site_url.'/catalog/'.$lRes["cat"].'-'.$lRes["anchor_cat"].'/'.$lRes["id_goods"].'-'.$lRes["anchor_goods"].'.html</loc>
	<lastmod>'.$date.'</lastmod>
</url>
';
		}

		
		$Db->query="SELECT * FROM `mod_gallery` WHERE `act`='1' ORDER BY `date` DESC";
		$Db->query();
		
		if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	
		{					
			$date = date("Y-m-d", strtotime($lRes["date"]));
			echo '<url>
				<loc>'.$site_url.'/gallery/'.$lRes["anchor"].'.html</loc>
				<lastmod>'.$date.'</lastmod>
			</url>
';
		}


		
		$Db->query="SELECT * FROM `mod_news` WHERE `act`='1' ORDER BY `date` DESC";
		$Db->query();
		
		if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	
		{					
			$date = date("Y-m-d", strtotime($lRes["date"]));
			echo '<url>
				<loc>'.$site_url.'/news/'.$lRes["anchor"].'.html</loc>
				<lastmod>'.$date.'</lastmod>
			</url>
';
		}
		
echo '</urlset>';
?>