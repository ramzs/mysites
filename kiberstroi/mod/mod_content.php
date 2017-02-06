<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
	$filter = new filter;
	$page_name = substr($filter->html_filter(@$param[1]), 0, -5);

	if (!@$page_name) {
		$page_active = 'index';
		$Db->query="SELECT `text` FROM `mod_content` WHERE id_content='1' LIMIT 1";
		$Db->query();
		$lRes=mysql_fetch_assoc($Db->lQueryResult);
		$normtext=str_replace("../","/",stripslashes($lRes[text]));
		$content.= $normtext;

	}

	else {
$Db->query="SELECT `id_content`,`title`,`keys`,`meta`,`name`,`text`,`anchor` FROM `mod_content` WHERE `anchor`='".$page_name."' LIMIT 1";
$Db->query();
if (mysql_num_rows($Db->lQueryResult)>0) {
	$lRes=mysql_fetch_assoc($Db->lQueryResult);
	$normtext=stripslashes($lRes[text]);
	$page_active = $lRes[anchor];
	$title = $lRes['name'].' - '.$config["main"]["main_title"];
	$keys = $lRes['name'].','.$config["main"]["main_keys"];
	$meta = $lRes['name'].' - '.$config["main"]["main_meta"];
	$parent_id = $lRes[id_content];
	$name=$lRes[name];
	$subpages='';
	
	// определяем подстраницы для вывода на выбранной странице
	$Db->query="SELECT `anchor`,`name`,`parent` FROM `mod_content` WHERE `act`='1' AND `parent`='$parent_id'";
	$Db->query();
	if (mysql_num_rows($Db->lQueryResult)>0) {
		$subpages='<ul class="subpages">';
		while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
			$subpages.='<li><a href="/content/'.$lRes["anchor"].'.html">'.$lRes["name"].'</a></li>';
		}	
		$subpages.='</ul>';
	}
	$content.= '<div class="breadcrombs"><a href="/">Главная</a> / '.$name.'</div>';
	$content.= '<h1>'.$name.'</h1>'.$subpages.$normtext;
	if (@$page_name == "obratnaya_svyaz") {
		include "inc/mail.php";
	}
	if (@$page_name == "akcii") {
		include "inc/sale.php";
	}
/*	elseif (@$page_name=="karta_saita") {
		include "inc/map.php";
	}	
	elseif (@$page_name=="podbor_online") {
		include "inc/online_podbor.php";
	}
	else {	*/
}
	else
	{
		header('HTTP/1.0 404 not found');
		$content = "<h1>Ошибка.</h1><p>Страница не найдена.</p>";
	}

	}
include("inc/header.php");
echo $content;
include("inc/footer.php");
?>