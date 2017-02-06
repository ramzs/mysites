<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");
$middle_class = $page_name ? 'temp="temp" class="wide"' : '';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="windows-1251" />
	<title><? if (empty($title)) echo $config["main"]["main_title"]; else echo $title;?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="keywords" content="<? if (empty($keys)) echo $config["main"]["main_keys"]; else echo $keys;?>" />
	<meta name="description" content="<? if (empty($meta)) echo $config["main"]["main_meta"]; else echo $meta;?>" />
	<meta name="google-site-verification" content="AIY8vlOkf2COHBgr8G4Njtlpv20NErIbGI98GUmKU7U" />
	<meta name='yandex-verification' content='67b6e3359a1cf4fb' />
    <link href="/css/style.css" rel="stylesheet" />
    <link href="/css/media.css" rel="stylesheet" />
    <link href="/css/contact.css" rel="stylesheet" />
    <link href="/css/pushy.css" rel="stylesheet" />
    <link href="/css/contact.css" rel="stylesheet" />
    <link href="/fancybox/jquery.fancybox.css" rel="stylesheet" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="/js/pushy.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
<script>
	$(function() {
		if (document.location.href != "http://kiberstroi.ru/") {$('#middle').addClass('wide');}
	});
</script>
<!-- Start SiteHeart code -->
<script>
(function(){
var widget_id = 762799;
_shcp =[{widget_id : widget_id}];
var lang =(navigator.language || navigator.systemLanguage
|| navigator.userLanguage ||"en")
.substr(0,2).toLowerCase();
var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
var hcc = document.createElement("script");
hcc.type ="text/javascript";
hcc.async =true;
hcc.src =("https:"== document.location.protocol ?"https":"http")
+"://"+ url;
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hcc, s.nextSibling);
})();
</script>
<!-- End SiteHeart code -->
<div class="wrap">
<div id="wrapper">
	<header>
		<div id="header_inner">
			<div class="map_block">
				<img src="/images/imagemap.jpg" width="1800" height="430" usemap="#map" alt="Киберстрой" />
				<map name="map">
					<area alt="Спутниковое ТВ" shape="poly" href="/catalog/7-sputnikovoe_tv/" class = "cat_07" coords="1040,176,1036,178,1034,182,1032,187,1032,194,1032,201,1034,206,1037,212,1039,214,1042,216,1048,218,1054,221,1061,217,1068,213,1069,213,1071,213,1071,215,1072,217,1074,217,1076,217,1077,215,1079,213,1079,209,1080,206,1075,204,1070,203,1068,204,1067,204,1067,206,1067,208,1069,209,1071,210,1068,211,1065,213,1059,216,1054,219,1053,219,1053,218,1056,216,1060,214,1062,210,1064,206,1064,199,1063,193,1061,188,1059,183,1057,180,1055,178,1051,176,1047,174,1045,174,1043,174">
					<div>Спутниковое ТВ</div>
					<area alt="Автоматика для ворот" shape="poly" href="/catalog/2-avtomatika_dlya_vorot/" class = "cat_02" coords="1261,357,1261,360,1259,360,1256,359,1246,358,1237,357,1229,357,1222,357,1222,356,1222,354,1240,354,1258,354,1259,353,1261,353">
					<div>Автоматика для ворот</div>
					<area alt="Автоматические ворота" shape="poly" href="/catalog/1-avtomaticheskie_vorota/" class = "cat_01" coords="1263,291,1258,294,1258,297,1258,301,1219,301,1179,301,1179,335,1179,370,1203,371,1227,372,1242,373,1257,374,1257,379,1258,385,1269,385,1280,385,1280,341,1280,298,1281,296,1281,294,1275,291,1269,289,1089,294,1085,295,1085,297,1086,300,1086,319,1087,338,1087,361,1087,385,1097,385,1106,385,1106,381,1106,377,1108,376,1111,375,1124,374,1137,373,1150,372,1163,371,1163,336,1163,301,1141,302,1119,303,1112,303,1106,303,1106,299,1106,295,1101,294,1097,292,1095,292,1094,292">
					<div>Автоматические ворота</div>
					<area alt="Шлагбаумы" shape="poly" href="/catalog/3-shlagbaumy/" class = "cat_03" coords="1381,274,1381,279,1383,279,1385,279,1385,276,1385,272,1398,272,1411,272,1411,271,1411,270,1399,270,1387,270,1384,269,1381,269">
					<div>Шлагбаумы</div>
					<area alt="Автоматические ворота" shape="poly" href="/catalog/4-rolstavni/" class = "cat_01 roll1" coords="635,269,635,299,654,299,674,298,695,297,716,296,716,269,716,243,707,243,698,242,678,242,658,241,646,240,635,240">
					<div>Автоматические ворота</div>
					<area alt="Рольставни" shape="poly" href="/catalog/4-rolstavni/" class = "cat_04 roll2" coords="991,256,991,278,1003,278,1014,278,1014,257,1014,236,1011,236,1008,236,999,235,991,235,1021,255,1021,277,1035,277,1049,277,1049,256,1049,235,1043,235,1038,235,1029,234,1021,234">
					<div>Рольставни</div>
					<area alt="Видеонаблюдение" shape="poly" href="/catalog/4-rolstavni/" class = "cat_05 video1" coords="1161,233,1161,235,1163,236,1165,238,1167,238,1169,239,1170,238,1171,237,1171,235,1171,233,1170,233,1169,232,1165,232,1161,231">
					<div>Видеонаблюдение</div>
					<area alt="Видеонаблюдение" shape="poly" href="/catalog/5-videonablyudenie/" class = "cat_05 video2" coords="640,230,640,233,642,236,643,238,648,238,652,238,654,234,657,231,655,229,654,227,647,227,640,227">
					<div>Видеонаблюдение</div>
					<area alt="Видеонаблюдение" shape="poly" href="/catalog/5-videonablyudenie/" class = "cat_05 video3" coords="955,205,954,206,948,206,942,206,941,208,939,210,942,213,944,217,948,217,953,217,956,212,960,207,959,205,958,204,956,204,955,204">
					<div>Видеонаблюдение</div>
					<area alt="Gsm сигнализации" shape="poly" href="/catalog/6-gsm_signalizacii/" class = "cat_06" coords="694,297,685,298,671,298,658,298,646,299,635,299,635,312,635,324,639,323,643,323,671,322,700,320,708,320,716,320,716,308,716,296,709,296,703,296">
					<div>Gsm сигнализации</div>
					<area alt="Хотите вызвать замерщика?" shape="poly" href="#" class = "meter contact" coords="430,251,427,253,426,256,425,260,427,266,429,272,427,274,425,276,425,277,425,278,423,278,420,278,415,282,410,285,409,289,408,292,407,302,406,313,406,318,407,324,409,327,412,329,413,332,414,335,415,340,416,346,415,355,414,364,414,369,415,374,414,379,412,384,412,384,412,385,419,385,425,385,425,383,425,381,428,376,430,371,430,366,430,362,432,356,434,350,435,350,435,351,434,363,432,376,431,380,430,384,430,384,430,385,436,385,443,385,444,380,445,376,447,374,448,372,448,358,448,344,449,339,450,335,450,334,451,334,452,334,452,335,452,340,453,346,453,347,454,347,455,346,457,344,456,334,456,324,455,314,454,304,451,296,449,289,446,284,444,280,441,279,439,278,439,274,440,270,441,264,443,259,442,257,442,254,439,252,437,249,435,249,432,249">
					<div class="cls">Закажите звонок или замерщика</div>
				</map>
			</div>
			<a href="/" id="logo"></a>
			<div class="slogan"><? if (!empty($banners["1"])) foreach ($banners["1"] as $key=>$value) echo $value;?></div>
			<div class="phone">
				<p><?=$config["main"]["main_adress"]?></p>
				<span>+7 (8452) </span><b><?=$config["main"]["main_phone"]?></b>
			</div>
			<div id="bucket">
				<a href="/order/">Корзина заказа</a>
				<div>Зайдите в каталог, затем добавьте <br>в корзину нужный вам товар</div>
			</div>
			<div class="btn">
				<a href="tel:711722">&Pcy;&ocy;&zcy;&vcy;&ocy;&ncy;&icy;&tcy;&softcy; &scy;&iecy;&jcy;&chcy;&acy;&scy;</a>
			</div>
			<div class="menu_mob">
				<div class="pushy pushy-left">
				<ul>
					<li><a href="/">&Gcy;&lcy;&acy;&vcy;&ncy;&acy;&yacy;</a></li>
					<li><a href="/content/o_kompanii.html">&Ocy; &kcy;&ocy;&mcy;&pcy;&acy;&ncy;&icy;&icy;</a></li>
					<li><a href="/catalog/">&Kcy;&acy;&tcy;&acy;&lcy;&ocy;&gcy;</a></li>
					<li><a href="/gallery/">&Gcy;&acy;&lcy;&iecy;&rcy;&iecy;&yacy;</a></li>
					<li><a href="/news/">&Ncy;&ocy;&vcy;&ocy;&scy;&tcy;&icy;</a></li>
					<li><a href="/content/akcii.html">&Acy;&kcy;&tscy;&icy;&icy;</a></li>
					<li><a href="/content/kontakty.html">&Kcy;&ocy;&ncy;&tcy;&acy;&kcy;&tcy;&ycy;</a></li>
				</ul>
				</div>
			</div>
			<div class="site-overlay"></div>

            <?
            $Db->query="SELECT `anchor`,`name`,`redirect`,`cat` FROM `mod_content` WHERE `act`='1' AND `parent`='0' AND  `in_menu`='1' ORDER BY `rank`";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0)
			{
				$footer_menu = '';
				echo '<nav>
						<ul>';
				while ($lRes=mysql_fetch_array($Db->lQueryResult)) {
					if (empty($lRes["redirect"])) $href = '/content/'.$lRes["anchor"].'.html'; else $href = $lRes["redirect"];
					if (($lRes["anchor"]==$page_active)||($lRes[redirect]==('/'.$page_active) )) echo '<li><span>'.$lRes["name"].'</span>';
					else echo '<li><a href="'.$href.'">'.$lRes[name].'</a></li>';

					$footer_menu.='<li><a href="'.$href.'">'.$lRes[name].'</a></li>';
				}
					echo '</ul>
				</nav>';
			}

				$Db->query="SELECT `id_cat`,`anchor_cat`,`name_cat`,`parent`,`img_cat` FROM `mod_catalog_cat` WHERE act='1' AND parent='0' AND img_cat!='' ORDER BY rank";
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {
					$catalog = '<ul id="services">';
					$dop_style = '<style>';
					while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
					{
						$catalog.= '<li class = "cat_0'.$lRes["id_cat"].'">
							<div style="background: url(\'/upload/cat/'.$lRes["img_cat"].'.png\') no-repeat 50% 0;"></div>
							<a href="/catalog/'.$lRes["id_cat"].'-'.$lRes["anchor_cat"].'/"><span>'.$lRes["name_cat"].'</span></a>
						</li>';

						$dop_style.= '#middle.two_sidebars #services .cat_0'.$lRes["id_cat"].' a:before  {
										width: 46px;
										height: 53px;
										top: -5px;
										background: url(\'/upload/cat/'.$lRes["img_cat"].'.png\') no-repeat 0 100%;
										background-size: 100%;
									}';
					}
					$dop_style.= '</style>';
					$catalog.= '</ul>';
				}

				$Db->query="SELECT * FROM `mod_news` WHERE act='1'  ORDER BY date DESC LIMIT 3";
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {

					$news = '<div id="news_block">
								<h2>Новости&nbsp;и&nbsp;акции</h2>
								<ul>';

					while ($lRes=mysql_fetch_assoc($Db->lQueryResult))
					{
						$date = news_oc_date($lRes["date"]);
						$news.= '<li>
							<div class="date">'.$date["day"].'<div>'.$date["month"].'</div></div>
							<a href="/news/'.$lRes["anchor"].'.html">
								'.$lRes["name"].'
								<span class="specify">подробнее</span>
							</a>
						</li>';
					}

					$news.= '</ul>
						</div>';
				}


			?>
		</div>
		<div class="menu-btn">
			<span class="text">&Ocy;&tcy;&kcy;&rcy;&ycy;&tcy;&softcy; &mcy;&iecy;&ncy;&yucy;</span>
			<div class="toggle">
				<div class="navicon-line"></div>
				<div class="navicon-line"></div>
				<div class="navicon-line"></div>
			</div>
		</div>
		<div class="menu_cat">
			<a href="/catalog/">&Mcy;&iecy;&ncy;&yucy; &kcy;&acy;&tcy;&iecy;&gcy;&ocy;&rcy;&icy;&jcy;</a>
			<img src="/images/menu-cat.png" alt="" />
		</div>
	</header>

<? if ($page_active=="index") echo '<div id="middle">'.$catalog.'<aside class="left_block">
			<form action="/search/" class="searchbar" method="post">
				<input type="text" placeholder="Поиск по сайту" name="query" />
				<input type="submit" value="">
			</form>'.$news.'</aside>
		<div id="content">';
else { ?>
<div id="middle" class="two_sidebars wide">
		<aside class="left_block">
			<form action="/search/" class="searchbar" method="post">
				<input type="text" placeholder="Поиск по сайту" name="query" />
				<input type="submit" value="" />
			</form>
            <? echo $dop_style.$catalog;
				if ($page_active != 'news') echo $news;
			?>

		</aside>
		<?
			if ($current_cat_id!='') {
				echo '<aside class="right_block">';
				$Db->query="SELECT `id_docs`,`name`,`anchor`,`img`,`anons` FROM `mod_docs` WHERE `cat_docs`='$current_cat_id' AND `act`='1' ORDER BY RAND() LIMIT 3";
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {
					echo '
					<h2><i class="fa fa-pencil"></i> Статьи по теме</h2>';
					while($docRes=mysql_fetch_assoc($Db->lQueryResult)) {
						if ($docRes["img"]!='') $img_docs=$docRes["img"]; else $img_docs="empty";
						echo '
						<a href="/docs/'.$docRes['anchor'].'.html" class="theme_docs">
							<div class="imgWrap"><img src="'.$img_docs.'" alt="'.$docRes['name'].'"/></div>
							<span class="docsName">'.$docRes['name'].'</span>
							<span class="docsAnons">'.substring($docRes['anons'],150).'</span>
						</a>';
					}
					echo '<a href="/docs/" class="docsAll">все статьи >></a>';
				}

				$Db->query="SELECT mod_catalog.*, mod_catalog_cat.id_cat,mod_catalog_cat.anchor_cat
							FROM `mod_catalog`
							LEFT JOIN mod_catalog_cat ON (mod_catalog_cat.id_cat=mod_catalog.cat) WHERE mod_catalog.cat='$current_cat_id'
							AND mod_catalog.act='1' AND mod_catalog.hot='1' ORDER BY RAND() LIMIT 2";
				$Db->query();
				if (mysql_num_rows($Db->lQueryResult)>0) {
					echo '
					<h2 class="icon_star">Супер Предложения</h2>';
					while($hitRes=mysql_fetch_assoc($Db->lQueryResult)) {
						if ($hitRes["img_good"]!='') $img=$hitRes["img_good"]; else $img="empty";
						echo '
						<div class="good_hit">
						<img src="/upload/goods/bg'.$img.'.jpg" alt="'.$hitRes['name_goods'].'">
						<span class="good_hit_price">'.$hitRes['price'].' руб</span>
						<a href="/catalog/'.$hitRes["id_cat"].'-'.$hitRes["anchor_cat"].'/'.$hitRes["id_goods"].'-'.$hitRes["anchor_goods"].'.html" class="hit_link">'.$hitRes['name_goods'].'</a>
						</div>';
					}
				}
				echo '</aside>';
			}?>
		<div id="content">
<? } ?>