<? 
$PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); 
// ���������� ��������� get �������
	$filter = new filter;
	if (what_ras($param[1])=="html") $page_name = substr($filter->html_filter(@$param[1]), 0, -5); else $page_str = $filter->html_filter(@$param[1]);
	$page_var = $filter->html_filter(@$param[0]);

	$page_active = 'otzyvy';


// ���� �� ����� �������� ����������� �������
if (!@($page_name)) {	
			// ������� ��� ������������ ���������
			$num = 20; // ���-�� ��������� �� ��������	
			$page = @$page_str;
			$Db->query="SELECT COUNT(id_online) FROM `mod_online` WHERE `act`='1'"; 
			$Db->query();
			$lRes=mysql_fetch_assoc($Db->lQueryResult);
			$posts = $lRes["COUNT(id_online)"]; //���-�� �� ������ ���������
			$total = (($posts - 1) / $num) + 1;
			$total =  intval($total); // ����� ����� �������
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;
  			if($page > $total) $page = $total;
			$start = $page * $num - $num;
			if ($start<0) $start=0;
			

		$content.= "<h1>������</h1>";
	
				
		$Db->query="SELECT * FROM `mod_online` WHERE `act`='1' ORDER BY `date` DESC LIMIT $start, $num";
		$Db->query();
					if (!@$_POST["submit_online"]) {
					$footer_script=5;
					$content.= "
					<form class='required-form' method='post'>
						<div id='fastorder'>
							<table width='100%' border='0' cellspacing='0' cellpadding='0'>
							  <tr>
								<td valign='top' width='60%' style='padding-right:50px'>
								<p><input type='text' name='name' class='required' placeholder='���� ���' size='45' /></p>
								<p><textarea name='quest' class='required_text' placeholder='��� �����' /></textarea></p></td>
								<td valign='top'>
								<img id='captchaimage' src='/inc/captcha.php' style='margin-top: 20px;' align='middle' /><br />
								<input type='text' class='required' name='captcha' size='14' placeholder='���' style='width: 93%;' />
								</fieldset>
							<center><input class='button' style='margin-top: 19px;' type='submit' name='submit_online' value='�������� �����'/></center></td>
							  </tr>
							</table>
						</div>
					</form>";
					}
					else
					{
						$filter = new filter; 
						$captcha = $filter->html_filter($_POST["captcha"]);
						$name = $filter->html_filter($_POST["name"]);
						$quest = $filter->html_filter($_POST["quest"]);
						if (strtolower($captcha)!=$_SESSION["code"]) 
						{
							$content.= "<a href='javascript:history.back();' class='red'><< �����</a><div class='errormsg'><br />������� ������ ����������� ���.</div>";
						}
						else
						{
							if (!empty($name))
							{
								if (!empty($quest))
								{
								mysql_query("INSERT INTO `mod_online` VALUES('','".$name."','','".$quest."','','".$cat."',NOW(), '0')");
							
								$objMail = new sent_mail();
								$objMail->to = array($config["main"]["main_email"]);
								$objMail->from = "info@gamesfactor.ru";
								$objMail->subject = "����� ����� �� �����";
								$objMail->body = "�� ����� �������� ����� �����. �������������� ��� �� ������ � ��������������� ������� ������� ����������.";
								$objMail->send();
							
							$content.= "<p>���  ����� ������� ���������.</p>";
								}
								else $content.= "<a href='javascript:history.back();' class='red'><< �����</a><div class='errormsg'><br />�� �� ����� ��� �����.</div>";
							}
							else $content.= "<a href='javascript:history.back();' class='red'><< �����</a><div class='errormsg'><br />�� �� ����� ���� ���.</div>";
						}
					}
					$content.= '<h1>��������� ������:</h1>';
				
		if (mysql_num_rows($Db->lQueryResult)>0) {
	
			while ($lRes=mysql_fetch_assoc($Db->lQueryResult))	 {

					$content.= '<div class="one_quest"><p class="the_name">'.$lRes['name_online'].'</p>
					<p class="sam_quest"><i>"'.$lRes["quest"].'"</i></p>
					</div>
					';
				}
			
				if ($total>1) {
					for ($i=1; $i<=$total; $i++) { 
						if ($page!=$i)  $navi.='<a href=/online/'.$i.'/>[ '.$i.' ]</a> | '; 
						else $navi.= '<b>'.$i.'</b> |';
					}	
				}
				$content.="<div class='sub_categ'>".$navi."</div>";
			}
			else $content.="<p>������� ���, ��� ����� ������.</p>";
			
}

include("inc/header.php");
echo $content;
include("inc/footer.php");
?>