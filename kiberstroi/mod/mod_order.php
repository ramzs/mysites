<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
	$edit_array = array("confirm", "success", "error", "pay"); //����������� ��������
// ���������� ��������� get �������
	$filter = new filter;
	$page_name = substr($filter->html_filter(@$param[1]), 0, -5);
	
	if (!@$page_name) {
	$content.= '<div class="breadcrombs"><a href="/">�������</a> / ������� ������</div><h1>������� ������</h1>';	

			if (@$_COOKIE['basket']) 
			{
				$basket = substr($_COOKIE['basket'], 0, strlen($_COOKIE['basket']) - 1 );
				$arr = explode(",",$basket);
				for($i = 0; $i < count($arr); $i++) $goods[] = explode(":", $arr[$i]);
				for($i = 0; $i < count($goods); $i++) $cartMass[$goods[$i][0]] = $goods[$i][1];
			}
			if ($cartMass=="") {
				$content.= "<p align='center'>���� ������� �����.</p>";
				  if($_GET["cookie"]) 
				  { 
					// �������� ��������� ������������� �� ��������, 
					// � ������� ����� ����������� ������� ���������� cookie  
					header("Location: http://kiberstroi.ru/order/?cookie=1"); 
					// ������������� cookie � ������ "test" 
					setcookie("test","1");  
				  } 
				  else 
				  { 
					if(!$test) 
					{ 
					  $content.= '<p>��� ���������� ������ ������� ���������� �������� cookies � ����� ��������.</p>
					  <p>�������������� ����������� �� ������, ���� ������� ���� ���������� ������, ����� ��� �������� ���������� ��� � ������� �����.</p>
					  <table width="100%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
							<td valign="top"><strong>��� �������� cookies � �������:</strong><br />
							<a href="http://help.yandex.ru/yabrowser/security/privacy-features.xml#cookies" target="_blank" rel="nofollow" style="color: #246cb3;">Yandex</a><br />
							<a href="http://help.yandex.ru/newbrowser/personal-data-protection/cookies.xml" target="_blank" rel="nofollow" style="color: #246cb3;">����� ������� Yandex</a><br />
							<a href="http://help.yandex.ru/common/browsers-settings/cookie-chrome.xml" target="_blank" rel="nofollow" style="color: #246cb3;">Google Chrome</a><br />
							<a href="http://help.yandex.ru/common/browsers-settings/cookie-ff.xml" target="_blank" rel="nofollow" style="color: #246cb3;">Mozilla Firefox</a><br />
							<a href="http://help.yandex.ru/common/browsers-settings/cookie-opera.xml" target="_blank" rel="nofollow" style="color: #246cb3;">Opera</a><br />
							<a href="http://help.yandex.ru/common/browsers-settings/cookie-ie.xml" target="_blank" rel="nofollow" style="color: #246cb3;">Internet Explorer</a><br />
							<a href="http://help.yandex.ru/common/browsers-settings/cookie-safari.xml" target="_blank" rel="nofollow" style="color: #246cb3;">Safari</a><br />
							</td>
							<td valign="top" class="required-form">
								<strong>���������� ������:</strong><br /><br />
								<input name="name" type="text" id="name_basket" size="40" placeholder="���� ���" />
								<input name="phone" type="text" id="phone_basket" size="40" placeholder="��� �������" />
								<input type="button" style="float:right; margin-top:10px; margin-right:15px;" class="button redbut" value="���������" id="send_basket_cookies" />
							</td>
						  </tr>
						</table>

					  '; 
					} 
					else 
					{ 
					  // cookie ��������, ��������� �� ������ ��������: 
					 // header("Location: http://kiberstroi.ru/order/"); 
					  // ����� ���������� ���������, ���������� ����� ������ �������� 
					} 
				  } 
			}
			else 
			{
				$cartCount=count($cartMass);
				$cartTotal=0;
				$cartTotalPrice=0;
							// ���� ������� � �������
			$content.="
			<form method='post' name='form1' action='/basket.php?action=refrash'>";
			$list = "<table width='100%' border='0' cellspacing='0' cellpadding='3' class='whiteborder'>
			<tr class='basket_head'>
			<td width='50'></td>
			<td>������������</td>
			<td width='120'>����</td>
			<td width='60'>���-��:</td>
			<td width='16'></td>
			</tr>";
			$number = 1;
				foreach($goods as $v) 
				{
					$Db->query="SELECT * FROM mod_catalog LEFT JOIN mod_catalog_cat ON mod_catalog.cat=mod_catalog_cat.id_cat WHERE id_goods=".$v['0'];
					$Db->query();
					if (mysql_num_rows($Db->lQueryResult)>0) 
						{
						$lRes=mysql_fetch_assoc($Db->lQueryResult);
						if ($lRes['img_good']!='')
						{
							$img_good='<img src="/upload/goods/sm'.$lRes['img_good'].'.jpg" height="50" align="middle">';
						}
						else $img_good='<img src="/upload/goods/smempty.jpg" height="50" align="middle">';
						$list.="
						<tr height='25'>
						<td width='30'>$img_good</td>
						
						<td><a href='/catalog/".$lRes['id_cat']."-".$lRes['anchor_cat']."/".$lRes['anchor_goods'].".html' class='blue' style='color: #000'>".$lRes['name_goods']."</a></td>
						<td width='120'>".$lRes['price']." ���.</td>
						
						<td width='80' class='count_td'><input name='kolvo[".$number."]' type='text' size='2' value='".$v[1]."' /> ".$lRes['units']."</td>
						<td class='center_img' width='16'><a href='#' title='������� �� �������' onclick='Delete(\"".$lRes['id_goods']."\",\"".$v[3]."\",\"".$v[4]."\")'><img src='/images/delete.png' border='0' alt='' class='pnghack' /></a></td>
						</tr>";
						$cartTotalPrice=$cartTotalPrice+$lRes['price']*$v['1'];
						$cartTotal = $cartTotal+$v['1'];
						$number++;
						}
				}
			$savePrice = $cartTotalPrice;
			$list.="
			
			<tr id='insert_before' data-price='".$config["main"]["dostavka"]."'>
			<td style='border-top:1px solid #aaa;'></td>
			<td class='itogo inright'  style='border-top:1px solid #aaa;'> �����:&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td class='itogo' style='border-top:1px solid #aaa;'><span id='total_summ'>".$cartTotalPrice."</span> ���.</td>
			<td style='border-top:1px solid #aaa;' colspan=2></td>
			</tr></table>";
			
			/*
			<td class='itogo' colspan='2'  style='border-top:1px solid #aaa;'>".$cartTotal." ��.</td>
			*/
			
			$content.=$list;
			// ����������� ��������
			$content.="
			<p class='inright'><input hspace='8' name='clear' type='button' value='��������' class='button' onclick='clear_cookie()' />
			<input hspace='8' name='refresh' type='submit' class='button' value='�����������' />
			<input hspace='8' name='ok' type='button' class='button redbut' value='��������' id='btn-slide' />&nbsp;&nbsp;</p>
			</form>\n";
			// ���������� ����� �������������
			$footer_script=1;
			$content.='<div style="display:none" id="panel">
			<p>���� ���������� <font color=red>*</font> ����������� ��� ����������.</p>
			<form class="required-form" method="post" action="/order/confirm.html">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
    		<td width="180"><label for="name">�������� <font color=red>*</font></label>:</td>
    		<td>
				<input type="hidden" value="0" name="town" id="change_now" />
				<select name="town" id="change_town">
					<option value="1">��������� �� ������</option>
					<option value="2">�������� �� �.�������� � �.��������</option>
					<option value="3">�������� � ������ ������</option>
				</select>
				<div class="dos_engels">�������� ��������, ��� �������� ������� ('.$config["main"]["dostavka"].' ���.)</div>
			</td>
  			</tr>
			<tr>
    		<td width="180" valign="top"><label for="name">������ <font color=red>*</font></label>:</td>
    		<td>
				<select name="pay" id="change_pay">
					<option value="1">������ ���������</option>
					<option value="2">������ online</option>
					<option value="3">��������� ��� ������ � �����</option>
					<option value="4">���� ��� ������������ ����</option>
				</select>
			</td>
  			</tr>
			<tr class="myhide">
    		<td width="180" valign="top"><label for="name">�������� �����������: </label></td>
    		<td>
				<input type="text" name="yur_name" value="" class="name_ooo" size="60" />
			</td>
  			</tr>
			<tr class="myhide">
    		<td width="180" valign="top"><label for="name">��������� �����������: </label></td>
    		<td>
				<textarea name="yur_rek" class="rekviz"></textarea><br />
			</td>
  			</tr>
  			<tr>
    		<td width="180"><label for="name">��� <font color=red>*</font>:</label></td>
    		<td><input name="name" type="text" id="name" size="40" class="required" required /></td>
  			</tr>
  			<tr>
    		<td><label for="phone">������� <font color=red>*</font>:</label></td>
    		<td><input name="phone" type="text" class="required" size="40" required /></td>
  			</tr>
  			<tr>
   			<td><label for="mail">E-mail:</label></td>
    		<td><input name="mail" type="text" id="email" size="40" /></td>
  			</tr>
  			<tr>
    		<td><label for="address">����� �������� <font color=red>*</font>:</label></td>
    		<td><input name="address" type="text" class="required" size="40" required /></td>
  			</tr>
			<tr>
    		<td><label for="text">����������� � ������:</label></td>
    		<td><textarea name="text" /></textarea></td>
  			</tr>
			</table>
			<input type="hidden" name="summ" value="'.$savePrice.'">
			<p align="right"><input class="button" name="submit_my_order" type="submit" value="���������"></p><br />
			</form>
			</div>';
			}
			$page_active = 'basket_page';
	}
	else
	{
		if (in_array($page_name, $edit_array))
		{
			if ($page_name=="success")
			{
				if (@$_POST["submit_success"])
				{
					$content = "<script type='text/javascript'>
					document.cookie = 'basket=0; path=/; expires=Wed, 1 Jan 1970 00:00:01 GMT';
					function redirect(){ 
 					location='/'; 
					} 
					setTimeout('redirect()', 5000); 
					</script>";
					$Db->query="UPDATE `mod_order` SET `finish`='1' WHERE `id_order`='".$_POST['id']."'";
					$Db->query();	
					$content.= "<h1>���������� �������.</h1><div class='text'><p class='succes'>�������, ��� ����� ������. � ��������� ����� ��� �������� �������� � ���� ��� ��������� ������ � ��������� �������� ������, � ��� �� ������� ������ �� ������.</p></div>";
					
					
					$subject = '����� ����� � ��������-��������';
					$message = '������������! �� ����� '.$DomenName.' ����� �����. ���������� ����������� ������, � ����� ���������� ���������� ����������� �� ������ � ��������������� ������� ������� �����������������.';
							
								$headers= "MIME-Version: 1.0\r\n";
								$headers.= "Content-type: text/html; charset=windows-1251\r\n";
								$headers.= "From: ".$DomenName." <noreplay@noreplay.ru>";	
			
			
					mail($config["main"]["main_email"], $subject, $message, $headers);

					file_get_contents("http://sms.ru/sms/send?api_id=1036c775-4d1f-ba84-993a-9f73658d4658&to=79873838577&text=".urlencode(iconv("windows-1251","utf-8","����� ����� � ����� ��������-��������")));
					
				}
				else
				{
					$mrh_pass1 = "2Hq59Kp3uN123";
					// ������ ����������
					// read parameters
					$out_summ = $_REQUEST["OutSum"];
					$inv_id = $_REQUEST["InvId"];
					$shp_item = $_REQUEST["Shp_item"];
					$crc = $_REQUEST["SignatureValue"];

					$crc = strtoupper($crc);

					$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

					// �������� ������������ �������
					// check signature
					if ($my_crc != $crc)
					{
  						$content = "<h1>������</h1>������ �� ������.\n";
					}
					else
					{
					$content = "<script type='text/javascript'>
					document.cookie = 'basket=0; path=/; expires=Wed, 1 Jan 1970 00:00:01 GMT';
					function redirect(){ 
 					location='/'; 
					} 
					setTimeout('redirect()', 10000); 
					</script>";
					$content.= "<h1>���������� �������.</h1><div class='text'><p class='succes'>��� ����� ������� �������. � ��������� ����� y�� �������� �������� � ���� ��� ��������� ������ � ��������� �������� ������, � ��� �� ������� ������ �� ������.</p></div>";
					}
				}
			}
			if ($page_name=="error")
			{
				$content = "�� ���������� �� ������. ����� �$inv_id\n";
			}
			
			if ($page_name=="confirm")
			{
				if (!@$_POST["submit_my_order"])
				{
					$content = "<h1>������ �������</h1><p>�� ����� �� ������ �������� ��� ����������.</p>";
				}
				else
				{
					$send_array = array(1=>"��������� �� ������ ", 2=>"�������� �� �.�������� � �.�������� (".$config["main"]["dostavka"]." ���.)", 3=>"�������� � ������ ������");
					$pay_array = array(1=>"������ ���������", 2=>"������ on-line", "��������� ��� ������ � �����", "���� ��� ������������ ����");
					$town_array = array(1=>"�������", 2=>"�������");
					
						$content = "<h1>������������� ������</h1><div class='text'>";
						$filter = new filter; 
						$name = $filter->html_filter($_POST["name"]);
						$phone = $filter->html_filter($_POST["phone"]);
						$address = $filter->html_filter($_POST["address"]);
						$time = $filter->html_filter($_POST["time"]);
						$date = $filter->html_filter($_POST["date"]);
						$text = $filter->html_filter($_POST["text"]);
						$town = $filter->html_filter($_POST["town"]);
						$yur_name = $filter->html_filter($_POST["yur_name"]);
						$yur_rek = $filter->html_filter($_POST["yur_rek"]);
						
							if (is_email($_POST["mail"])) $mail = $_POST["mail"]; else $mail = "";
							if (!empty($name)) $content.= "<p>���� ���: <i>$name</i></p>";
							if (!empty($phone)) $content.= "<p>��� �������: <i>$phone</i></p>";
							if (!empty($mail)) $content.= "<p>��� �����: <i>$mail</i></p>";
							if (!empty($address)) $content.= "<p>����� ��������: <i>$address</i></p>";
							if (!empty($date)) $content.= "<p>���� ��������: <i>".formatedpost($date, false)."</i></p>";
							if (!empty($time)) $content.= "<p>����� ��������: <i>".$time."</i></p>";
							if (!empty($text)) $content.= "<p>�������������� ���������: <i>$text</i></p>";
						$content.="<p><i>".$send_array[$_POST[town]]."</i></p>";
						$content.="<p><i>".$pay_array[$_POST[pay]]."</i></p>";
						
						if ($_POST[town]==2) $_POST["summ"] = $_POST["summ"] + $config["main"]["dostavka"];
						$content.="<p>����� � ������: <b class='orange'>".$_POST["summ"]." ���.</b></p>";
							if ($_POST["pay"]!=2) {
								$content.= "<p><br />������� �� ������ �������� ������, �� ������������ � ��������� ��������</p>";
							}				
							$basket = substr($_COOKIE['basket'], 0, strlen($_COOKIE['basket']) - 1 );
							$arr = explode(",",$basket);
							for($i = 0; $i < count($arr); $i++) $goods[] = explode(":", $arr[$i]);
							for($i = 0; $i < count($goods); $i++) $cartMass[$goods[$i][0]] = $goods[$i][1];
							$cartCount=count($cartMass);
							$cartTotal=0;
							$cartTotalPrice=0;
							$list = "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='whiteborder'>
							<tr class='basket_head'>
							<td width='30'>�</td>
							<td>������������</td>
							<td width='50'>���-��:</td>
							<td width='120'>���� �� ��., ���</td>
							<td width='120'>�����, ���.</td>
							</tr>";
							$number = 1;
								foreach($cartMass as $k=>$v) 
								{
								$Db->query="SELECT `name_goods`,`price`,`id_goods`,`code` FROM `mod_catalog` WHERE `id_goods`='$k'";
								$Db->query();
									if (mysql_num_rows($Db->lQueryResult)>0) 
									{
									$lRes=mysql_fetch_assoc($Db->lQueryResult);
									$list.="
									<tr height='30'>
									<td>$number</td>
									<td>".$lRes['name_goods']."</td>
									<td>$v</td>
									<td>".$lRes['price']."</td>
									<td>".$v*$lRes['price']."</td>
									</tr>";
									$cartTotalPrice=$cartTotalPrice+$lRes['price']*$v;
									$cartTotal = $cartTotal+$v;
									$number++;
									}
								}
							if ($town==2) 
							{
								$cartTotalPrice = $cartTotalPrice+$config["main"]["dostavka"];	
									$list.="
								<tr height='30'>
									<td>$number</td>
									<td>�������� �� �.�������� � �.��������</td>
									<td></td>
									<td></td>
									<td>".$config["main"]["dostavka"]."</td>
									</tr>";
							}
							
							$list.="
							<tr>
							<td class='itogo right' colspan='3'></td>
							<td> �����: </td>
							<td class='itogo'><span>".$cartTotalPrice."</span></td>
							</tr></table>";
							
						$Db->query="INSERT INTO mod_order (list,name,mail,adress,phone,text,summ,date,finish,send,pay,yur_name,yur_rek) VALUES  ('".mysql_escape_string($list)."','".$name."','".$mail."','".$address."','".$phone."','".$text."','".$_POST["summ"]."',NOW(),'0','".$town."','".$_POST[pay]."','".$yur_name."','".$yur_rek."')";
						$Db->query();
						$id = mysql_insert_id();
						
						$hash = pass_solt(generateCode(10));
						$_SESSION['hash']=$hash;
						$_SESSION['access']=1; 
						
						$c = base64_encode($_SESSION["hash"]."|".$_SESSION['access']);
						$key_order = pass_solt($_SESSION["hash"]);
						$id_order = base64_encode($id);
									
						if ($_POST[pay]==4) $content.="<a href='/inc/print_02.php?key=".$key_order."&id=".$id_order."&c=".$c."' target='_blank' class='print_doc'>������������ ����</a>";
						if ($_POST[pay]==3) $content.="<a href='/inc/print_01.php?key=".$key_order."&id=".$id_order."&c=".$c."' target='_blank' class='print_doc'>������������ ���������</a>";
						
					
						if ($_POST["pay"]!=2) {
							$content.= '<form action="/order/success.html" method="post"><p align="center"><input class="button" name="submit_success" type="submit" value="������" style="float: right; margin-right: 50px;"  /><input type="hidden" name="id" value="'.$id.'"></form></div>';
						}
						else
						{	
								// ��������������� ���������� (�����, ������ #1)
							$mrh_login = "Kiberstroi.ru";
							$mrh_pass1 = "2Hq59Kp3uN123";
							// ����� ������
							$inv_id = $id;
							// �������� ������
							$inv_desc = "������ ������ �".$id;
							// ����� ������
							$out_summ = $_POST["summ"];
							// ��� ������
							$shp_item = "";
							// ������������ ������ �������
							$in_curr = "";
							// ����
							$culture = "ru";
							// ������������ �������
							$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
							// ����� ������ ������
							
							$content.= '
							<form action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">
							<input name="MrchLogin" type="hidden" value="'.$mrh_login.'" />
							<input name="OutSum" type="hidden" value="'.$out_summ.'" />
							<input name="InvId" type="hidden" value="'.$inv_id.'" />
							<input name="Desc" type="hidden" value="'.$inv_desc.'" />
							<input name="SignatureValue" type="hidden" value="'.$crc.'" />
							<input name="Shp_item" type="hidden" value="'.$shp_item.'" />
							<input name="IncCurrLabel" type="hidden" value="'.$in_curr.'" />
							<input name="Culture" type="hidden" value="'.$culture.'" />
							<p align="center"><input type="submit" value="�������� �����" class="button" style="float: right; margin-right: 50px;"  /></p>
							</form>';
						}
						
							$content.= '<input class="button" type="button" value="��������� �����" onclick="goBack()" style="float: right; margin-right: 50px;" />';
					
				}
			}
		}
		else
		{
			exit("<html><head><meta  http-equiv='Refresh' content='0; URL=/order/'></head></html>");
		}
	}
include("inc/header.php");
echo $content;
include("inc/footer.php");
?>