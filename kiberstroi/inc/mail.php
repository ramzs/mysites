<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied");  
if (!@$_POST["send"]) {

$content.= '
	<div class="obratka">
		<form class="required-form" method="post">
				<label>
					<span style="line-height:40px">���� ���: </span><br />
					<input type="text" name="firstname" class="required" required="required" size="40" />
				</label>
				<label>
					<span style="line-height:40px">Email: </span><br />
					<input type="text" name="mymail" class="required email" required="required" size="40" />
				</label>
				<label>
			</fieldset>
			<fieldset>
				<legend style="line-height:40px">����� ���������: </legend>
					<textarea name="comments" class="required" required="required" /></textarea>
				</label><br /><br />
				<span>������ �� �������: </span>
				<img id="captchaimage" src="/inc/captcha.php" align="middle" /> <input type="text" name="captcha" style="width:200px; float:right; margin-right: -24px;"  />
			</fieldset>
			<center><input class="button" type="submit" name="send" value="���������" style="width:104%" /></center>
		</form>
	</div>';
}
else
{
	if (is_email($_POST["mymail"])) $email_ok = $_POST["mymail"]; else $email_ok=""; // ������ ��� ����. java
	if ($_POST["captcha"]==$_SESSION['code'])
	{
			$filter = new filter; 
			$name = $filter->html_filter($_POST["firstname"]);
    		$comments = $filter->html_filter($_POST["comments"]);
			$body = "���������� ����������: ���: ".$name.", E-amil: ".$email_ok."<br>����� ���������: ".$comments;
			
					$objMail = new sent_mail();
  					$objMail->to = array($config["main"]["main_email"]);
  					$objMail->from = "info@gamesfactor.ru";
  					$objMail->subject = "��� ������ � ����� gamesfactor.ru";
  					$objMail->body = $body;
  					$objMail->send();
					$content.= "<p>���� ��������� ������� ����������</p>";
	}
	else $content.= "<p class='red'>�������� ����������� ���. ��������� � ��������� �������.</p>";
}
?>
