<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");

			$edit_array = array("edit","delete","delete_all","config","pereschet");
			parse_str($_SERVER['QUERY_STRING']);
				$send_array = array(1=>"Самовывоз со склада ", 2=>"Доставка по г.Саратову и г.Энгельсу (".$config["main"]["dostavka"].")", 3=>"Доставка в другие города");
				$pay_array = array(1=>"Оплата наличными", 2=>"Оплата Online", 3=>"Квитанция на оплату", 4=>"Счет для юр лица");
				
				
if (!in_array($action, $edit_array)) { //главная страница редактирования модуля

			// запросы для постраничной навигации
			$num = $config["order"]["page_in_admin"]; // кол-во выводимых на страницу 
			$page = @$page;
			$Db->query="SELECT COUNT(id_order),SUM(summ) FROM mod_order"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_order)"]; //кол-во 
			$summ = $lRes["SUM(summ)"];
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // общее число страниц
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			
			$Db->query="SELECT * FROM `mod_order` ORDER BY `date` DESC LIMIT $start, $num";
			$Db->query();
			$Db->query();
			if (mysql_num_rows($Db->lQueryResult)>0) {
			$content_mod.= '<h4>Всего заказов: '.$posts.', на сумму '.$summ.' руб.</h4><br /><form method="post" action="index.php?mod=order&action=pereschet" name="form1"> 
			<table border="0" cellspacing="0" cellpadding="0" id="my-list" width="100%"><tr class="head"><td>Имя</td><td>Сумма</td><td>Дата заказа</td><td width="60" class="nobg"><div class="conf"><img src="img/icons/trash.png" class="pnghack" align="middle" hspace="7" /></div></td></tr>';
			
				$num=1;
				while ($lRes=mysql_fetch_assoc($Db->lQueryResult)) {
					$content_mod.= '<tr class="one_news">
								<td><a href="index.php?mod=order&action=edit&id='.$lRes["id_order"].'">'.$lRes["name"].'</a></td>
								<td>'.$lRes["summ"].' руб.</td>
								<td width="120">'.formatedpost($lRes["date"]).'</td>
								<td><div class="conf">';
		$content_mod.= "<input type='checkbox' value='1' name='delete[".$lRes['id_order']."]' class='checkbox'  />
		</div>";
								$content_mod.= '</td>
								</tr>';
								$num++;
				}
				for ($i=1; $i<=$total; $i++) { 
					if ($page!=$i) $navi.='<a href=index.php?mod=order&action=list&page='.$i.'>'.$i.'</a> | '; else $navi.= '<b>'.$i.'</b> |';
				}
								
				$content_mod.= '<tr><td></td><td></td><td><input src="img/icons/tick_red_icon.png" align="middle" class="pnghack" type="image" hspace="7" /></td><td>
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
				});
			</script>
			<div class="conf"><input type="checkbox" value="1" class="checkbox" id="selall" /></div></td></tr></table></form>';
			
			if ($total > 1)	$content_mod.="<table class=\"pstrnav\"><tr><td class='all_page'>".$navi."</td></tr></table>";

			}
			else
			{
				$content_mod = "Заказов нет.";
			}
				
}
else
{
	if ($action=="pereschet")
	{
		//print_r($_POST);
		
		if(!empty($_POST["delete"]))
		{
			 $query = "(" ;
			 foreach($_POST["delete"] as $key=>$val) $query.= "$key,";
			 $query = substr($query, 0, strlen($query) - 1 ). ")" ;
			 $Db->query="DELETE FROM `mod_order` WHERE `id_order` IN ".$query;
			 $Db->query();
		}
		
		
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=order&action=list'></head></html>");
	}
	if ($action=="edit")
	{
		$Db->query="SELECT * FROM `mod_order` WHERE id_order='".$id."' LIMIT 1";
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			if ($lRes['act']==1) $chek = " checked";
			if ($lRes['main']==1) $chek_main = " checked";
			if (empty($lRes["date"])) { $date = date("m/d/Y"); $time=date("H:i:s");} else { $date = date("m/d/Y", strtotime($lRes["date"])); $time=date("H:i:s", strtotime($lRes["date"])); }
			if (@$page) $pagestr = "&page=".$page; else $pagestr = "";
			$content_mod = "
			<h3>Заказ: <strong>№".$lRes[id_order]."</strong>, дата заказа - ".formatedpost($lRes['date'])."</h3><div class='clear'></div>";
			$content_mod.= '
			<table border="0" cellspacing="0" cellpadding="0">
  			<tr height="30">
    		<td width="40%"><p>Имя:</p></td>
    		<td width="60%">'.$lRes["name"].'</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Контактные данные:</p></td>
    		<td width="60%">'.$lRes["phone"].', '.$lRes["adress"].', '.$lRes["mail"].'</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Оплата:</p></td>
    		<td width="60%">'.$pay_array[$lRes["pay"]].'</td>
  			</tr>';
			if ($lRes["pay"]==4)
			{
				$content_mod.= '<tr height="30">
								<td width="40%"><p>Имя и реквизиты Юридического лица:</p></td>
								<td width="60%">'.$lRes["yur_name"].'<br />'.$lRes["yur_rek"].'</td>
								</tr>';
			}
			
			$content_mod.= '<tr height="30">
    		<td width="40%"><p>Доставка:</p></td>
    		<td width="60%">'.$send_array[$lRes["send"]].'</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Сумма заказа:</p></td>
    		<td width="60%">'.$lRes["summ"].'</td>
  			</tr>
			<tr height="30">
    		<td width="40%"><p>Комментарии к заказу:</p></td>
    		<td width="60%">'.$lRes["text"].'</td>
  			</tr>
			</table><p>&nbsp;</p>'.$lRes["list"];
		
	}
	if ($action=="delete_all")
	{
        $Db->query="TRUNCATE TABLE `mod_order`"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=order'></head></html>");
	}	
	if ($action=="delete")
	{
        $Db->query="DELETE FROM `mod_order` WHERE `id_order` = '".$id."'"; 
		$Db->query();
		exit("<html><head><meta  http-equiv='Refresh' content='0; URL=index.php?mod=order'></head></html>");
	}	
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
				if($lRes[type]=="text") $val = ' value=\''.$lRes[value].'\''; else $val = '';
				$content_mod.= '<p><input class="check" name="'.$lRes[option].'" type="'.$lRes[type].'"'.$chek.$val.' size="60" /> '.$lRes[name].'</p>';
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

echo $content_mod;

// необходимые функции для этого модуля
?>