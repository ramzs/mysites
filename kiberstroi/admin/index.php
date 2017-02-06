<?php
session_start();
$_SESSION["file_info"] = array();
//unset($_SESSION["file_need"]);
require_once("db.php");
require_once($SITE_ROOT."/mod/mod_func.php");
$Db=new Db ($DBServer,$DBLogin,$DBPassword,$DBName);

    $rec_url=$_SERVER['REQUEST_URI'];
	$param=explode("/",$rec_url);
	$param=Clear_array($param);
	$filter = new filter; 
	$p1 = explode("?", $param[1]);
	$p2 = explode("&", $p1[1]);
	$p3 = explode("=", $p2[0]);
	$need_mod = $p3[1];
	
$Db->connect();
mysql_query("SET NAMES 'cp1251'");
require_once("auth.php");

if ($autorized != 1)
	{	// если не нажата кнопка входа
		if (!@$_POST["login_submit"]) {
		// хедер админки
		$content = "<div id='main_block'>
		<div class='header'>
			<div class='logo'><img src='img/logo.jpg' align='left' /><span>CherryCMS</span></div>
			<div class='site'>Перейти на <a href='/' target='_blank'>".$DomenName."</a><br /><br /><a href='index.php'>Главная страница системы</a></div>
			<div class='whois'>Вы зашли как: <b>Гость</b><br /><span class='small'>введите данные для управления сайтом</span><br /></div>
		</div>
		";
		
		$content.= '
		<div class="clear"></div>
		
		<form action="index.php?mod=content" method="post" class="loginform">
  		<h2>Вход в систему администрирования</h2> <br />
  		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  			<tr>
			<td>
			<input type="text" name="email" size="50" value=""><img src="img/icons/user.png" class="pnghack" align="middle" hspace="10" /><br /><br />
			<input type="password" name="pass" size="50" value=""><img src="img/icons/unlock.png" class="pnghack" align="middle" hspace="10" /><br /><br />
			<input name="link" type="hidden" value="'.$_SERVER['REQUEST_URI'].'" />
			<input name="login_submit" value="Отправить" type="submit" id="but" />
			</td>
			</tr>
		</table></form>';
		}
		else // если нажата кнопка входа, логиним
		{
			$ip=RealIP(); 
			$Db->query="DELETE FROM error_login WHERE UNIX_TIMESTAMP() - UNIX_TIMESTAMP(date) > 900";
        	$Db->query();
			$Db->query="SELECT col FROM error_login WHERE ip='".$ip."'";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['col'] > 2) {
            //если ошибок больше двух, т.е три, то выдаем сообщение.
            $content = "<h1>Внимание! Ошибка!</h1><p>Вы набрали логин или пароль неверно 3 раза. Подождите 15 минут до следующей попытки.</p>";
            }      
			else
			{
    			$filter = new filter; 
				if (is_email($_POST["email"])) $login_ok = $_POST["email"]; else exit ("Неверное поле - логин");
    			$pass_ok = $filter->html_filter($_POST["pass"]);
				$Db->query="SELECT * FROM `mod_users_admin` WHERE `mail`='".$login_ok."' AND `act_user`='1' LIMIT 1";
				$Db->query();
				$lRes=mysql_fetch_assoc($Db->lQueryResult);
    			if (empty($lRes['pass']))
    			{
					$Db->query="SELECT ip FROM error_login WHERE ip='".$ip."'";
					$Db->query();
					$lRes=mysql_fetch_assoc($Db->lQueryResult);
						if ($ip == $lRes[0]) {
						$Db->query="SELECT col FROM error_login WHERE ip='".$ip."'";
						$Db->query();
						$lRes=mysql_fetch_assoc($Db->lQueryResult);         
						$col = $lRes[0] + 1;
						$Db->query="UPDATE `error_login` SET `col`='".$col."',`date`=NOW() WHERE `ip`='".$ip."'";
						$Db->query();
						}          
						else 
						{
							$Db->query="INSERT INTO error_login (ip,date,col) VALUES  ('".$ip."',NOW(),'1')";
							$Db->query();
						}      
    				$content = exit("Введенный вами данные не верны.");
    			}
    			else 
				{
					$pass_solt = pass_solt($pass_ok);
					if ($lRes['pass']==$pass_solt) {				
						$hash = pass_solt(generateCode(10));
						$access = pass_solt($lRes['access']);
						$Db->query="UPDATE `mod_users_admin` SET `user_hash`='".$hash."' WHERE `id_user`='".$lRes['id_user']."'";
						$Db->query();
						$_SESSION['id_user']=$lRes['id_user']; 
						$_SESSION['hash_user']=$hash;
						$_SESSION['access_user']=$access;
						$Db->query="UPDATE `mod_users_admin` SET `date`=NOW() WHERE `id_user`='".$lRes['id_user']."'";
						$Db->query();
						
							$Db->query="INSERT INTO `mod_stat` (`name`, `date`) VALUES ('Пользователь ".$lRes['name_user']." вошел в систему', NOW())";
							$Db->query(); 
						exit("<html><head><meta  http-equiv='Refresh' content='0; URL=".$_POST["link"]."'></head></html>");					
					}
					else 
					{
					$content = exit("Введённый вами данные не верны.(code:82)");
					}
    			}
			}
		}
	}
	else
	{ // если выполнен выход
		if (@$_GET['logout'] && $_GET['logout']==true)
		{
			$Db->query="UPDATE `mod_users_admin` SET `date`=NOW() WHERE `id_user`='".$global_user."'";
			$Db->query();
			unset($_SESSION['id_user']);
			unset($_SESSION['hash']);
			$Db->query="INSERT INTO `mod_stat` (`name`, `date`) VALUES ('Пользователь ".$status." вышел из системы', NOW())";
			$Db->query();
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php'></head></html>");
		}

		// вытаскиваем все включенные модули и их страницы в соотстветвие с нашим доступом
		$Db->query="SELECT * FROM `modules` 
					LEFT JOIN `modules_conf` ON (modules.id_mod=modules_conf.rel_mod)
					LEFT JOIN `modules_access` ON (modules_conf.id_conf_mod=modules_access.rel_mod_conf)
					WHERE `act_mod`='1' AND `act_admin_mod`='1'  AND `view`='1' AND rel_user='".$global_user."' ORDER BY `rank`,`rank_conf`";
		$Db->query();
		$mod = "";
		if (mysql_num_rows($Db->lQueryResult)>0) 
		{
			$mod_name = '';
		while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) 
			{ 
			//print_r($lRes);
				
				if ($mod_name!=$lRes['name_mod']) 
				{
					if ($mod_name != '') $mod.= '</div>';
					$mod.= "<li class='one_cat' title='#".$lRes['name_mod']."'><img src='img/icons/".$lRes['img'].".png' class='pnghack' align='middle' />".$lRes['title_mod']."</li><div id='".$lRes['name_mod']."' class='hidden_mod";
					if ($lRes['name_mod'] == $need_mod) $mod.= ' not_hide';
					$mod.= "'>";
					
				}
				$mod.= "<div class='podmod'>- <a href='index.php?mod=".$lRes['name_mod']."&action=".$lRes['action']."'>".$lRes[name_conf]."</a></div>";
				
				$pages[] = $lRes["name_mod"];
				$mod_name = $lRes["name_mod"];
			}
			$mod.= '</div>';
		}
		else $pages = array();
		// хедер админки
		$content = "<div id='main_block'>
		<div class='header'>
			<div class='logo'><img src='img/logo.jpg' align='left' /><span>CherryCMS</span></div>
			<div class='site'>Перейти на <a href='/' target='_blank'>".$DomenName."</a><br /><br /><a href='index.php'>Главная страница системы</a></div>
			<div class='whois'>Вы зашли как: <b>".$status."</b> (<a href='?logout=true'>Выход</a>)<br /><span class='small'>(последнее посещение: $mydate)</span><br /></div>
		</div>
		";
		$content.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="block">
  		<tr>
   		<td valign="top" class="left_menu">'.$mod;
		
		$content.= '</td>
    	<td valign="top" class="txt">
		';
		// для исключений 
		$array_not = array("pereschet", "pereschet_cat", "down", "up", "delete_img", "delete_img_report", "putarchive", "addpoll", "addanswform","delquest","addansw","delansw","hide","show","edit_answer","pereschet_news", "delete_catalog_logo","delete_cat_img","delete_company_img", "pereschet_company", "delete_scroll_img","delete_good_cover","delete_goods_img","edit_brand","list_brand","delete_brand_logo","pereschet_brand","delete_manual","editscroll","deletescroll","listscroll","delete_cover","delete_cover2", "delete_img","list_online","edit_online","delete_online", "delete_dop","edit","delete","delete_slider_img","delete_gallery_cover","delete_gallery_img", "pereschet_filter", "edit_podfilter", "delete_filter");
		
		// какой модуль открыт
		$filter = new filter; 
		$mod = $filter->html_filter($_GET["mod"]);
		$action = $filter->html_filter($_GET["action"]); 
		if ($action=="edit") $action = "edit&id=new"; 
		else if ($action=="edit_cat") $action = "edit_cat&id=new";
		else if ($action=="edit_brand") $action = "edit_brand&id=new";
		else if ($action=="editscroll") $action = "editscroll&id=new";
		else if ($action=="edit_filter") $action = "edit_filter&id=new";
		else if ($action=="edit_podfilter") $action = "edit_podfilter&id=new";
		if (!isset($mod) || ($mod=="") || (!in_array($mod, $pages)) ) 
		{
			$mod="main";
			$content.= "<h1><img src='img/icons/info.png' align='middle' class='pnghack' /> Система администрирования сайта</h1>";
		}
		else
		{
			$Db->query="SELECT * FROM modules LEFT JOIN `modules_conf` ON (modules.id_mod=modules_conf.rel_mod) WHERE name_mod='".$mod."' AND action='".$action."' LIMIT 1";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
				if ($mod=="catalog" && $action=="list") $content.= "<div class='filter'>Фильтр</div>"; 
				$lRes=mysql_fetch_assoc($Db->lQueryResult);  
				$content.= "<h1><img src='img/icons/".$lRes['img'].".png' align='middle' class='pnghack' /> ".$lRes['title_mod']."";
				if ($id!="new") $content.= " / ".$lRes['name_conf']."</h1>"; else $content.= "</h1>";
			}
			//else if (!in_array($action,$array_not)) $mod="main"; 
		}
		
	}
	include "header.php";
	echo $content;
	if ($autorized != 0) include("admin_mod_$mod.php");
	include "footer.php";
?>