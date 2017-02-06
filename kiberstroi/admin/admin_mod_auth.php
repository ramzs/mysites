<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","config");
			$array_tip = array(1=>"Клиент");
			parse_str($_SERVER['QUERY_STRING']);
			
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля


			// запросы для постраничной навигации
			$num = 20; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_person) FROM mod_person"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_person)"]; //кол-во 
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start = 0;
			$Db->query="SELECT mod_person.mail,mod_person.reg_date,mod_person.who,mod_person.id_person,mod_person.act FROM `mod_person` ORDER BY `reg_date` DESC LIMIT $start, $num";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				$content_mod = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr class="head">
								<td>Кто</td>
								<td>Логин (e-mail)</td>
								<td>Дата регистрации</td>
								<td width="50"></td>
								</tr>';
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Активирован' title='Активирован' border=0 />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не активирован' title='Не активирован' border=0 />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td>'.$array_tip[$lRes["who"]].'</td>
								<td><a href="index.php?mod=auth&action=edit&id='.$lRes["id_person"].$pagestr.'">'.$lRes["mail"].'</a></td>
								<td>'.formatedpost($lRes["reg_date"]).'</td>
								<td>'.$act.'
								<a href="#" onclick="Delete_person('.$lRes["id_person"].')" title="Удалить"><img src="images/del.png" class="pnghack" border=0 /></a>
								</td>
								</tr>';
								$num++;
				}
				// Проверяем нужны ли стрелки назад
				if ($page != 1) $pervpage = '<a href="index.php?mod=auth&action=list&page='. ($page - 1) .'"><< Предыдущая страница</a>';
				// Проверяем нужны ли стрелки вперед
				if ($page != $total) $nextpage = '<a href="index.php?mod=auth&action=list&page='. ($page + 1) .'">Следующая страница >></a>';
				if ($total > 1)	$navigation="<table class=\"pstrnav\"><tr><td class='last'>".$pervpage."</td><td class='all_page'>Вы находитесь на странице: $page, всего страниц: $total</td><td class='next'>".$nextpage."</td></tr></table>";
				$content_mod.= "</table>".$navigation;
			}
			else
			{
				$content_mod = "Покупателей нет.";
			}
}
else
{
	if ($action=="edit")
	{
		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_person` WHERE id_person='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";

			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$content_mod = "
			
			<h3><strong>".$lRes[name]."</strong></h3><div class='clear'></div>";
			$content_mod.= '<form method="post" enctype="multipart/form-data" name="newsform"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Имя *:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name'])).'" size="50"> 						</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Логин * (email):</p></td>
    		<td width="60%"><input type="text" name="mail" value="'.htmlspecialchars(stripslashes($lRes['mail'])).'" size="50"> 						</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Телефон:</p></td>
    		<td width="60%"><input type="text" name="phone" value="'.htmlspecialchars(stripslashes($lRes['phone'])).'" size="50"> 						</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Адрес:</p></td>
    		<td width="60%"><input type="text" name="adress" value="'.htmlspecialchars(stripslashes($lRes['adress'])).'" size="50"> 						</td>
  			</tr>
			</table>
			<input class="check" name="passsend" type="checkbox" value="on" /> Сгенерировать пароль и выслать на почту<br /><br />
  			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активация по емаил<br /><br />
			
			<p><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form> ';
			$content_mod.=  "<h1>Заказы</h1><br />";
			$Db->query="SELECT * FROM `mod_order` WHERE `rel_person`='".$id."' ORDER BY `date` DESC";
				$Db->query();		
				if (mysql_num_rows($Db->lQueryResult)>0) 
					{
						$stat_array = array(0=>'Заказ в обработке <img src="/images/help.png" class="pnghack" alt="" border="0" align="middle" hspace="5" />', 1=>'Заказ принят <img src="/images/accept_item.png" class="pnghack" alt="" border="0" align="middle" hspace="5" />', 2=>'Оплата получена <img src="/images/dollar_currency_sign.png" class="pnghack" alt="" border="0" align="middle" hspace="5" />', 3=>'Заказ отправлен <img src="/images/right_arrow.png" class="pnghack" alt="" border="0" align="middle" hspace="5" />');
						$content_mod.=  '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr class="basket_head"><td width="100">Дата</td><td>Номер заказа</td><td>Сумма</td><td>Оплата</td><td>Статус</td></tr>';
						while ($lRes=mysql_fetch_array($Db->lQueryResult)) 
						{
							$id_order = $lRes["id_order"];
							
							switch ($lRes["pay"])
							{
								case 1:
									$pay='Наличными';
									break;
								case 2: 
									$pay="Счёт для юр. лица";
									break;
								case 3:
									$pay="Online-платёж";
									break;
							}
							
							$content_mod.= '<tr class="order_one">
								<td>'.news_date($lRes["date"]).'</td>
								<td><a target="_blank" href="index.php?mod=order&action=edit&id='.$id_order.'" class="listorder">Заказ №'.$lRes["id_order"].'</a></td>
								<td>'.$lRes["summ"].' руб.</td>
								<td>'.$pay.'</td>
								<td>'.$stat_array[$lRes["stat"]].'<br />'.formatedpost($lRes["stat_date"]).'</td>
							  </tr>';
						}
						$content_mod.= '</table>';
					}
					else $content_mod.= '<p>Заказов нет.</p>';
		}
		else //обрабатываем форму
		{
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			if (is_email($_POST["mail"])) $mail = $_POST["mail"]; else $mail = '';
			$phone = $filter->html_filter($_POST["phone"]);
			$adress = $filter->html_filter($_POST["adress"]);
			$who = $filter->html_filter($_POST["who"]);
			$text = mysql_escape_string(trim($_POST["text"]));
			$id = $_POST["id"];
			if (@$_POST["act"]) $act = 1; else $act = 0;
			
			if (!empty($mail) && @$_POST["passsend"])
			{
				$mypass = rand(10000, 99999);
				$pass = pass_solt($mypass);
				$subject = 'Регистрация на '.$DomenName;
           		$message = 'Здравствуйте! Вы зарегистрированы на сайте '.$DomenName.'. Используйте ваш e-mail в качестве логина в системе, пароль: '.$mypass.' \n\nС уважением, администрация '.$DomenName;
					$objMail = new sent_mail();
  					$objMail->to = array($mail);
  					$objMail->from = $MailRobot;
  					$objMail->subject = $subject;
  					$objMail->body = $message;
					$objMail->send();
			}
			else $pass = $_POST["pass"];

			$Db->query="INSERT INTO `mod_person` (`id_person`, `name`, `mail`, `text`, `act`, `phone`, `adress`, `who`, `access`,`reg_date`,`pass`)
						VALUES ('".$id."','".$name."','".$mail."','".$text."','".$act."','".$phone."','".$adress."','".$who."','1',NOW(),'".$pass."')
						ON DUPLICATE KEY UPDATE
						`id_person`=VALUES(`id_person`),
						`name`=VALUES(`name`),
						`mail`=VALUES(`mail`),
						`text`=VALUES(`text`),
						`act`=VALUES(`act`),
						`phone`=VALUES(`phone`),
						`adress`=VALUES(`adress`),
						`who`=VALUES(`who`),
						`pass`=VALUES(`pass`)";
						
			if($Db->query()) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=auth&action=list'></head></html>");
		}
	}
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_person` WHERE `id_person` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=auth&action=list'></head></html>");
	}
	if ($action=="config")
	{
		if (!@$_POST["save"])
		{
			$content_mod = '<h4>Настройки модуля:</h4>';
			$Db->query="SELECT * FROM `mod_config` WHERE `mod`='".$mod."'";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
			$content_mod.= "<form action='index.php?mod=".$mod."&action=config' method='post'>";
				while($lRes=mysql_fetch_assoc($Db->lQueryResult))
				{
				if($lRes[type]=="checkbox") $content_mod.= '<input type="hidden" name="'.$lRes[option].'" value="0">';
				if($lRes[type]=="checkbox" && $lRes["value"]=="on") $chek = ' checked="checked"'; else $chek = '';
				if($lRes[type]=="text") $val = ' value="'.$lRes[value].'"'; else $val = '';
				$content_mod.= '<p><input class="check" name="'.$lRes[option].'" type="'.$lRes[type].'"'.$chek.$val.' /> '.$lRes[name].'</p>';
				}
			$content_mod.= '<p><input type="submit" value="Обновить" class="but" name="save"></p>
			</form>';
			}
			else
			{
				$content_mod.= '<br /><p>Настройки для данного модуля не найдены.</p>';
			}
		}
		else
		{
			// обрабатываем форму сохранения настроек
			unset($_POST[save]);
			$query = '';
			foreach ($_POST as $key=>$value) $query.= " WHEN `option`='".$key."' THEN '".$value."'";	
			$Db->query="UPDATE `mod_config` 
			SET `value` = CASE ".$query."
			ELSE `value` END";
			$Db->query(); 
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=".$mod."&action=config'></head></html>");
		}
	}
}
echo $content_mod;
// необходимые функции для этого модуля
?>