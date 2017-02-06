<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("delete","edit","pereschet");
			parse_str($_SERVER['QUERY_STRING']);

if (!in_array($action, $edit_array)) { //главная страница редактирования модуля
		
		$Db->query="SELECT * FROM `mod_users_admin` ORDER BY id_user";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
				
				$content_mod = '<form method="post" action="index.php?mod=users&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Имя</td><td>Логин</td><td>Посл. заход</td><td width="130" class="nobg"><div class="conf"><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /><img src="img/icons/accept_item.png" class="pnghack" align="middle" /><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
				
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					
					if ($lRes['act']!=0) $act = "<img src='images/show.png' class='pnghack' alt='Показывается' title='Показывается' />"; else $act = "<img src='images/hide.png' class='pnghack' alt='Не показывается' title='Не показывается' />";
					if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=users&action=edit&id='.$lRes["id_user"].$pagestr.'">'.$lRes["name_user"].'</a></td>
								<td>'.$lRes["mail"].'</td>
								<td>'.formatedpost($lRes["date"]).'</td>
								<td><div class="conf">';
								
		$content_mod.= "<input type='hidden' value='0' name='act[".$lRes['id_user']."]' />";
		if ($lRes['act_user']!=0) $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_user']."]' class='checkboxact' checked='checked' />"; else $content_mod.= "<input type='checkbox' value='1' name='act[".$lRes['id_user']."]' class='checkboxact' />";
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_user']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				
				$content_mod.= '<tr><td></td><td></td><td></td><td>
				<script type="text/javascript">
				$(function () {
				
					$("#selall").live("click", function () {
						if (!$("#selall").is(":checked")){
							$(".checkbox").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkbox").attr("checked", true);
							$.uniform.update();
						}	
					});
					$("#selall_act").live("click", function () {
						if (!$("#selall_act").is(":checked")){
							$(".checkboxact").removeAttr("checked");
							$.uniform.update();
						}
						else{
							$(".checkboxact").attr("checked", true);
							$.uniform.update();
						}	
					});
				});
			</script>
			<div class="conf"><input type="checkbox" value="1" class="checkboxact" id="selall_act" /><input type="checkbox" value="1" class="checkbox" id="selall" /></div></td></tr></table></form>';
			
			}
			else
			{
				$content_mod = "Пользователей нет.";
			}
	}
	else
	{
		if ($action=="edit")
	{
		// все доступные модули
		$Db->query="SELECT * FROM `modules` 
					LEFT JOIN `modules_conf` ON (modules.id_mod=modules_conf.rel_mod)
					WHERE `act_mod`='1' AND `act_admin_mod`='1' AND `view`='1' ORDER BY `rank`,`rank_conf`";
		$Db->query();
		$data_pages = array();
		if (mysql_num_rows($Db->lQueryResult)>0) while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) $data_pages[$lRes['id_mod']][] = $lRes;

		if (!@$_POST["submit"]) { // если не нажата кнопка
			$Db->query="SELECT * FROM `mod_users_admin` WHERE `id_user`='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act_user']==1) $chek = " checked";

			$content_mod.= '<form method="post" enctype="multipart/form-data" name="docsform" action="index.php?mod=users&action=edit&id='.$id.'"> 
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Имя:</p></td>
    		<td width="60%"><input type="text" name="name" value="'.htmlspecialchars(stripslashes($lRes['name_user'])).'" size="80"> 						</td>
  			</tr>
			<tr height="30">
    		<td><p>Логин:</p></td>
    		<td><input type="text" name="mail" value="'.htmlspecialchars(stripslashes($lRes['mail'])).'" size="80">';
			if ($id=="new")
			{
				$content_mod.= '</tr><tr height="30">
				<td><p>Пароль:</p></td>
				<td><input type="text" name="pass_new" value="" size="80"></td>
				</tr>';
			}
			else
			{
				$content_mod.= '<br /><input class="check" name="sbross" type="checkbox" value="1" /> Сбросить пароль и выслать на почту<br /></td>
  			</tr>';
			}
			$content_mod.= '</table>
			<input type="hidden" name="old_pass" value="'.$lRes['pass'].'">
			<input class="check" name="act" type="checkbox"'.$chek.' value="on" /> Активность<br /><br />
			<input type="hidden" name="id" value="'.$id.'">
			 ';
			//print_r($data_pages);
			
			$Db->query="SELECT `rel_mod_conf` FROM `modules_access` WHERE `rel_user`='".$id."'";
			$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
						while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $acess_array[] = $lRes["rel_mod_conf"];
					}
					else $acess_array = array();
			
			
			$num_colum = 0;
			$content_mod.= '<div class="conf_access">';
			foreach ($data_pages as $key=>$value) 
			{
				if ($num_colum==4) {$content_mod.= '</div><div class="conf_access">';$num_colum=0;}
				$content_mod.= '<br /><b>'.$value[0]["title_mod"].'</b><br />';
				foreach ($value as $k=>$v) 
				{
					if (in_array($v["id_conf_mod"],$acess_array)) $content_mod.= '<input class="check" name="chek['.$v["id_conf_mod"].']" type="checkbox" checked="checked" value="on" />'.$v["name_conf"].'<br />'; else  $content_mod.= '<input class="check" name="chek['.$v["id_conf_mod"].']" type="checkbox" value="on" />'.$v["name_conf"].'<br />';
				}
				++$num_colum;
			}
			$content_mod.= '</div>
			<p style="clear:both"><br /><input type="submit" value="Сохранить" class="but" name="submit"></p>
			</form>
			';
		}
		else //обрабатываем форму
		{
			$filter = new filter; 
			$name = $filter->html_filter($_POST["name"]);
			if (is_email($_POST["mail"])) $email = $_POST["mail"]; else $email = "";
			$id = $_POST["id"];
			if (@$_POST["act"]) $act = 1; else $act = 0;
			
			if (!@$_POST["pass_new"])
			{
				if (@$_POST["sbross"] && !empty($email))
				{
					$rand = rand();
					$pass = pass_solt($rand);
					$objMail = new sent_mail();
					$objMail->to = array($email);
					$objMail->from = $MailRobot;
					$objMail->subject = 'Новый пароль на сайте Sovetov.su';
					$objMail->body = 'Ваш новый пароль: '.$rand.'.';
					$objMail->send();
				}
				else $pass = $filter->html_filter($_POST["old_pass"]);
			}
			else $pass = pass_solt($_POST["pass_new"]);

			$Db->query="INSERT INTO `mod_users_admin` (`id_user`, `name_user`, `mail`, `act_user`, `pass`)
						VALUES ('".$id."','".$name."','".$email."','".$act."','".$pass."')
						ON DUPLICATE KEY UPDATE
						`id_user`=VALUES(`id_user`),
						`name_user`=VALUES(`name_user`),
						`mail`=VALUES(`mail`),
						`act_user`=VALUES(`act_user`),
						`pass`=VALUES(`pass`)";
			$content_mod.= "<br /><br /><p align='center'><img src='/images/loader.gif' /></p>";
			$Db->query();
			
			if ($id=="new") $id = mysql_insert_id();
			
			foreach ($_POST["chek"] as $chek_id=>$chek_value) $chek[]=$chek_id;
			
					$Db->query="SELECT `rel_mod_conf` FROM `modules_access` WHERE `rel_user`='".$id."'";
					$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) {
						while($lRes=mysql_fetch_assoc($Db->lQueryResult)) $acess_array[] = $lRes["rel_mod_conf"];
					}
					else $acess_array = array();
			
			foreach ($data_pages as $key=>$value) 
			{
				foreach ($value as $k=>$v) 
				{
					if (in_array($v["id_conf_mod"],$chek)) 
					{
						if(!in_array($v["id_conf_mod"],$acess_array))
						{
							$Db->query="INSERT INTO `modules_access` (rel_mod_conf,rel_user) VALUES  ('".$v["id_conf_mod"]."','".$id."')";
							$Db->query();
						}
					}
					else  
					{
						if(in_array($v["id_conf_mod"],$acess_array))
						{
							$Db->query="DELETE FROM `modules_access` WHERE `rel_mod_conf`='".$v["id_conf_mod"]."' AND `rel_user`='".$id."'";
        					$Db->query();
						}
					}
				}
			}
			
			
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=users&action=list'></head></html>");
		}
	}
		if ($action=="pereschet")
		{
			//print_r($_POST);
			
			if(!empty($_POST["delete"]))
			{
				 $query = "(" ;
				 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
				 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
				 $Db->query="DELETE FROM `mod_users_admin` WHERE `id_user` IN ".$query;
				 $Db->query();
				 $Db->query="DELETE FROM `modules_access` WHERE `rel_user`='".$id."'";
        		$Db->query();
			}
			if(!empty($_POST["act"]))
			{
				 foreach($_POST["act"] as $key=>$val) 
				 {
					 $Db->query="UPDATE `mod_users_admin` SET `act_user` = '".$val."' WHERE id_user ='".$key."'";
					 $Db->query();
				 }
			}
			
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=users&action=list'></head></html>");
		}
		
	}

echo $content_mod;

// необходимые функции для этого модуля
?>