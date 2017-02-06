<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
// Определяем пременные get запроса
	$filter = new filter;
	$page_name = substr($filter->html_filter(@$param[1]), 0, -5);
	$page_active = 'news';
	
// Если не задан параметр конкретной новости
if (!@($page_name)) {
// Если не задан параметр категории выводим все новости всех категорий	
	$content.= '<div class="breadcrombs"><a href="/">Главная</a> / Новости</div>';
	$Db->query="SELECT * FROM `mod_news` WHERE `act`='1' ORDER BY date DESC";
	$Db->query();
$title = ($lRes['title']=='') ? 'Новости и акции - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
		$keys = ($lRes['keys']=='') ? 'новости,акции,'.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
		$meta = ($lRes['meta']=='') ? 'Новости и акции - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];		

	$division = "";
					$news = '<div id="news_block">
								<h1>Новости&nbsp;и&nbsp;акции</h1>
								<ul>';
					
					while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
					{
						if (!empty($lRes["img"])) $img = '<img src="'.$lRes["img"].'" />'; else $img = '<img src="/upload/news/empty.jpg" />'; 
						$date = news_oc_date($lRes["date"]);
						$news.= '<li>'.$img.'
							<div class="date">'.$date["day"].'<div>'.$date["month"].'</div></div>
							<a href="/news/'.$lRes["anchor"].'.html">
								'.$lRes["name"].'
								<span class="specify">подробнее</span>
							</a>	
						</li>';
					}
					
					$news.= '</ul>
						</div>';
	
	$content .= $news;
}
// Если задан параметр конкретной новости выводим ее
else {
$Db->query="SELECT * FROM `mod_news` WHERE `anchor`='".$page_name."' LIMIT 1";
$Db->query();
if (mysql_num_rows($Db->lQueryResult)>0) {
	$lRes=mysql_fetch_assoc($Db->lQueryResult);

	$title = ($lRes['title']=='') ? $lRes["name"].' - '.$config['main']['main_title'] : $lRes['title'].' - '.$config['main']['main_title'];
	$keys = ($lRes['keys']=='') ? $lRes["name"].','.$config['main']['main_keys'] : $lRes['keys'].' - '.$config['main']['main_keys'];
	$meta = ($lRes['meta']=='') ? $lRes["name"].' - '.$config['main']['main_meta'] : $lRes['meta'].' - '.$config['main']['main_meta'];		


	$normtext=str_replace("../","/",stripslashes($lRes[text]));
	$content.= '<div class="breadcrombs"><a href="/">Главная</a> / <a href="/news/">Новости</a> / '.$lRes["name"].'</div>';
	$content.= "<h1>".$lRes['name']."</h1>".$normtext."<p><br />Дата публикации: ".formatedpost($lRes['date'], false)."</p>";
	$lRes['view']++; $view=$lRes['view'];
	$Db->query="UPDATE `mod_news` SET `view`='".$view."' WHERE `anchor`='".$page_name."'";
	$Db->query();
} else {
header('HTTP/1.0 404 not found');
$content = "<h1>Ошибка.</h1><p>Страница не найдена.</p>";
}
}
include("inc/header.php");
echo $content;
include("inc/footer.php");
?>