<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
		$filter = new filter;
		if (what_ras($param[1])=="html") $page_name = substr($filter->html_filter(@$param[1]), 0, -5); else $page_str = $filter->html_filter(@$param[1]);
		unset($filter);
		$page_active = 'gallery';
	
	
	if (!@($page_name)) {
				$Db->query="SELECT * FROM mod_gallery WHERE mod_gallery.act='1' AND mod_gallery.cover!='' AND parent='0' ORDER BY mod_gallery.date DESC";
				$Db->query();
$title = ($lRes['title']=='') ? 'Галерея - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
		$keys = ($lRes['keys']=='') ? 'галерея,'.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
		$meta = ($lRes['meta']=='') ? 'Галерея - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];		

				$content ='<div class="breadcrombs"><a href="/">Главная</a> / Галерея</div>';
				$content.= "<h1>Галерея</h1><br />";
				$num_rows = mysql_num_rows($Db->lQueryResult);
				if ($num_rows>0) {
					$content.= '<ul class="gallery_cat">';
				while($lRes = mysql_fetch_assoc($Db->lQueryResult))  $content.='<li>
						<a href="/gallery/'.$lRes["anchor"].'.html"><img src="'.$lRes["cover"].'" alt="" /></a>
						<span>'.$lRes["name_gallery"].'</span>
					</li>';
					$content.= '</ul><div style="clear:both;"></div>';
			}
			else $content.="<p>Альбомов нет</p>";
			
	}
	else
	{
		$page_active = 'inalbum';
		if (what_ras($param[1])=="html") 
		{
			$Db->query="SELECT *
					FROM mod_gallery
					WHERE mod_gallery.anchor='".$page_name."'";
			$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {
					$lRes=mysql_fetch_assoc($Db->lQueryResult);
					mysql_query("UPDATE `mod_gallery` SET `view`='".$lRes['view']."'+1 WHERE `anchor`='".$page_name."' LIMIT 1");
					$normtext=str_replace("../","/",stripslashes($lRes["text_gallery"]));
					$normtext=str_replace('class="photo"', 'class="open" rel="group"', $normtext);
					$content ='<div class="breadcrombs"><a href="/">Главная</a> / <a href="/gallery/">Галерея</a> / '.$lRes['name_gallery'].'</div>';
					$content.= "<h1>".$lRes['name_gallery']."</h1>".$normtext;
					unset($normtext);
					$title = $lRes["title"];
					$meta = $lRes["meta"];
					$keys = $lRes["keys"];
					$id = $lRes["id_gallery"];
					
					$Db->query="SELECT *
								FROM mod_file_gallery
								WHERE mod_file_gallery.album='".$id."'";
					$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
							$content.= '<div class="gallery_in">';
								while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $content.= '<div class="one_project"><a class="fancybox" rel="group" href="/upload/gallery/bg'.$lRes["source"].'.jpg" title="'.$lRes["name_file"].'"><img src="/upload/gallery/'.$lRes["source"].'.jpg" alt="'.$lRes["name_file"].'" /></a></div>'; 
							$content.= '</div><div style="clear:both;"></div>';
					}
					//$content.= print_comment("gallery", $id, '', $lRes['name_gallery']);
					$content.= '';
					unset($lRes);
				}
				else
				{
					header('HTTP/1.0 404 not found');
					$content = "<h1>Ошибка.</h1><p>Страница не найдена.</p><div class='eror404'></div>";
				}
		}	
	}
include("inc/header.php");
echo $content; 
unset($content);
include("inc/footer.php");
?>