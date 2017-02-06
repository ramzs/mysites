<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array();
			parse_str($_SERVER['QUERY_STRING']);
			
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			if (!@$_POST["save"])
		{
			$Db->query="SELECT * FROM `mod_users_admin` WHERE `id_user`='".$global_user."' LIMIT 1";
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) 
			{
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$content_mod.= "<form method='post'><br /><br />
			<p><input type='password' name='mypass' value='' /> Текущий пароль</p><br />
			<p><input type='password' name='password' value='' /> Новый пароль</p><br />
			<p><input type='password' name='password2' value='' /> Новый пароль повторно</p><br />
 			";
			$content_mod.= '<p><input type="submit" value="Обновить" class="but" name="save"></p>
			</form>';
			}
			else
			{
				$content_mod.= '<br /><p>Неверные параметры профиля.</p>';
			}
		}
		else
		{
			// обрабатываем форму
			
			$filter = new filter; 
			$mypass = $filter->html_filter($_POST["mypass"]);
			$password = $filter->html_filter($_POST["password"]);
			$password2 = $filter->html_filter($_POST["password2"]);
			$Db->query="SELECT `pass`,`mail` FROM `mod_users_admin` WHERE `id_user`='".$global_user."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$pass_solt = pass_solt($mypass);
			if ($lRes['pass']==$pass_solt) 
				{
					$update_login = $login_ok;
					if (!empty($password)) 
					{
						if ($password==$password2){ $update_pass=pass_solt($password); $logout = 1; }
						else {exit("Введенные пароли не совпадают."); $update_pass = $lRes['pass']; $logout = 0; }
					}
				}
					else 
				{
					$update_login = $lRes["mail"];
					exit("Текущий пароль не верен.");
				}
			$Db->query="UPDATE `mod_users_admin` SET `pass`='".$update_pass."' WHERE `id_user` = '".$global_user."'";
			$Db->query(); 
			if ($logout == 1) exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?logout=true'></head></html>"); else exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=profile'></head></html>");
		}
}


echo $content_mod;
// необходимые функции для этого модуля
?>